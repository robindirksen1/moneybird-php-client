<?php

namespace Picqer\Financials\Moneybird\Entities;

use Picqer\Financials\Moneybird\Model;

/**
 * Class InvoiceDetail.
 */
class GeneralJournalDocumentEntry extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'id',
        'administration_id',
        'ledger_account_id',
        'contact_id',
        'debit',
        'credit',
        'row_order',
        'created_at',
        'updated_at',
    ];
}
