<?php
/**
 * This is a demo script for the functions of the PHP ESC/POS print driver,
 * Escpos.php.
 *
 * Most printers implement only a subset of the functionality of the driver, so
 * will not render this output correctly in all cases.
 *
 * @author Michael Billington <michael.billington@gmail.com>
 */
require 'escpos/autoload.php';
use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\EscposImage;

use Mike42\Escpos\PrintConnectors\DummyPrintConnector;

$connector = new WindowsPrintConnector($printer_name);
$printer = new Printer($connector);

/* Initialize */
$printer -> initialize();

/* Text */
$connector -> write("\x1B\x40");
$connector -> write("\x1B\x6B\x01");
$connector -> write("\x1B\x21\x9A");
$printer -> text(sprintf('%-16.16s', 'CV.PELITA SEJATI '));
$printer -> text(sprintf('%-9.9s', ''));
$printer -> text(sprintf('%-16.16s', 'FAKTUR PENJUALAN'));
$printer -> text(sprintf('%-14.14s', ''));
$printer -> text(sprintf('%25.25s', 'BANDUNG,02 SEPTEMBER 2020 '));
$printer -> text(sprintf('%25.25s', 'BANDUNG,02 SEPTEMBER 2020 '));
$printer -> text("\n");
/* Line feeds */
$connector -> write("\x1B\x21\x1C");
$printer -> text(sprintf('%-31.31s', 'JL.MAYOR SUNARYA NO 22, '));
$printer -> text(sprintf('%-3.3s', 'NO:'));
$printer -> text(sprintf('%-15.15s', 'FPJ020920-02550'));
$printer -> text(sprintf('%-3.3s', ''));
$printer -> text(sprintf('%44.44s', 'Kepada Yth,'));
$printer -> feed();
$connector -> write("\x1B\x69");


/* Always close the printer! On some PrintConnectors, no actual
 * data is sent until the printer is closed. */
$printer -> close();
