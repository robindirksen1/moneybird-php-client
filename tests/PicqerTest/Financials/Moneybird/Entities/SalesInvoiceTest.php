<?php

namespace PicqerTest\Financials\Moneybird\Entities;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;
use Prophecy\Argument\Token\AnyValueToken;
use Picqer\Financials\Moneybird\Connection;
use Picqer\Financials\Moneybird\Entities\SalesInvoice;

class SalesInvoiceTest extends TestCase
{
    /** @var SalesInvoice */
    private $salesInvoice;
    /** @var ObjectProphecy */
    private $connection;
    /** @var ObjectProphecy */
    private $options;
    /** @var array */
    private $optionsJson;

    protected function setUp()
    {
        parent::setUp();

        $this->connection = $this->prophesize(Connection::class);
        $this->options = $this->prophesize(SalesInvoice\SendInvoiceOptions::class);
        $this->optionsJson = [
            'my-key' => 'my value',
        ];
        $this->options->jsonSerialize()->willReturn($this->optionsJson);

        $this->salesInvoice = new SalesInvoice($this->connection->reveal());
    }

    public function testSendInvoiceThrowsExceptionWhenNonOptionsPassed()
    {
        try {
            $this->salesInvoice->sendInvoice(false);
            self::fail('Should have thrown exception');
        } catch (InvalidArgumentException $e) {
        }

        try {
            $this->salesInvoice->sendInvoice(new \stdClass());
            self::fail('Should have thrown exception');
        } catch (InvalidArgumentException $e) {
        }
    }

    public function testSendWithoutArguments()
    {
        $this->connection->patch(new AnyValueToken(), json_encode([
            'sales_invoice_sending' => [
                'delivery_method' => SalesInvoice\SendInvoiceOptions::METHOD_EMAIL,
            ],
        ]))->shouldBeCalled();

        $this->salesInvoice->sendInvoice();
    }

    public function testSendWithMethodAsString()
    {
        $this->connection->patch(new AnyValueToken(), json_encode([
            'sales_invoice_sending' => [
                'delivery_method' => SalesInvoice\SendInvoiceOptions::METHOD_EMAIL,
            ],
        ]))->shouldBeCalled();

        $this->salesInvoice->sendInvoice(SalesInvoice\SendInvoiceOptions::METHOD_EMAIL);
    }

    public function testSendWithOptionsObject()
    {
        $this->connection->patch(new AnyValueToken(), json_encode([
            'sales_invoice_sending' => $this->optionsJson,
        ]))->shouldBeCalled();

        $this->salesInvoice->sendInvoice($this->options->reveal());
    }
}
