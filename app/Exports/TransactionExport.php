<?php

namespace App\Exports;

use App\Models\TransactionHeader;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class TransactionExport implements FromView, ShouldAutoSize, WithStrictNullComparison, WithEvents, WithColumnFormatting
{
    use Exportable;

    private $dateStart;
    private $dateEnd;
    private $transactionType;
    private $wasteCategory;
    private $wasteBankId;
    private $counter = 0;

    public function __construct(string $dateStart,
                                string $dateEnd,
                                int $transactionType,
                                int $wasteCategory,
                                int $wasteBankId)
    {
        $this->dateStart = $dateStart;
        $this->dateEnd = $dateEnd;
        $this->transactionType = $transactionType;
        $this->wasteCategory = $wasteCategory;
        $this->wasteBankId = $wasteBankId;
    }

    /**
     * @return View
     */
    public function view(): View
    {
        error_log($this->transactionType. '_'. $this->wasteCategory. '_'. $this->wasteBankId);

        $transactions = TransactionHeader::whereBetween('date', array($this->dateStart, $this->dateEnd));

        $transactionTypeId = $this->transactionType;
        if($transactionTypeId != 0){
            $transactions = $transactions->where('transaction_type_id', $transactionTypeId);
        }

        $wasteCategoryId = $this->wasteCategory;
        if($wasteCategoryId != 0){
            $transactions = $transactions->where('waste_category_id', $wasteCategoryId);
        }

        if($this->wasteBankId != 0){
            $transactions = $transactions->where('waste_bank_id', $this->wasteBankId);
        }

        $transactions = $transactions->orderByDesc('date')
            ->get();
        $totalWeight = $transactions->sum('total_weight');
        $totalWeight = $totalWeight / 1000;

        $totalPrice = $transactions->sum('total_price');

        error_log($transactions->count());

        $this->counter = $transactions->count() * 2;

        $data = [
            'trxHeaders'    => $transactions,
            'totalWeight'   => $totalWeight,
            'totalPrice'    => $totalPrice

        ];

        return view('documents.transactions.transaction_excel', $data);
    }

    /**
     * @return array
     */
    public function columnFormats(): array
    {
        return [
            'A' => '@'
        ];
    }

    /**
     * @return array
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function(AfterSheet $event) {
                $cellRange = 'A1:W1'; // All headers
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle($cellRange)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $this->counter *= 2;

                $column1 = 'E2:G'. $this->counter;
                $event->sheet->getDelegate()->getStyle($column1)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            },
        ];
    }
}
