<?php declare(strict_types=1);

namespace App\Command;

use App\Formater\SonarPhpUnitFormater;
use Exception;
use Lib\CommandController;

/**
 * Class ConverterController
 * @package App\Command
 */
class ConverterController extends CommandController
{
    /**
     * @param array $argv
     *
     * @return mixed|void
     */
    public function run(array $argv)
    {
        $file = $argv[2] ?? 'report.xml';
        if (!file_exists($file)) {
            throw new Exception("Echec lors de l'ouverture du fichier $file.");
        }
        $formater = new SonarPhpUnitFormater($file);

        // Test with standard DOM
        $output = $formater->format();
        $this->getApp()->getPrinter()->display(sprintf($output));

        // Test with recursive DOM iterator
        $output = $formater->formatWithRecursiveIterator();
        $this->getApp()->getPrinter()->display(sprintf($output));

        return;
    }
}
