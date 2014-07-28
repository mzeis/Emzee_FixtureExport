<?php

interface Emzee_FixtureExport_Model_Export_HandlerInterface
{
    /**
     * Processes the row and returns the data that should be written to the output file.
     *
     * @param array $row
     * @return array
     */
    public function getDataToBeWritten(array $row);
}