<?php declare(strict_types=1);
use SebastianBergmann\Exporter\Exporter;
use SebastianBergmann\Exporter\ObjectExporter;

final class MoneyExporter implements ObjectExporter
{
    public function handles(object $object): bool
    {
        return $object instanceof Money;
    }

    public function export(object $object, Exporter $exporter, int $indentation): string
    {
        assert($object instanceof Money);

        return sprintf(
            'Money (%d %s)',
            $object->amount(),
            $object->currency(),
        );
    }
}
