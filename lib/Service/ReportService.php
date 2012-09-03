<?php

class ReportService
{

    public function getData($reportType, array $filters = array())
    {
        $dataFunction = 'getData' . sfInflector::camelize($reportType);

        return $this->$dataFunction($filters);
    }

   

    protected function procesQueryFetchAll($query, array $replacements = array())
    {
        $query = str_replace(array_keys($replacements), $replacements, $query);
        $connection = Propel::getConnection();
        $statement = $connection->prepare($query);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * @param string $reportType
     * @return ReportForm $reportForm
     */
    public function getForm($reportType)
    {
        $formClass = sfInflector::camelize($reportType) . 'ReportForm';

        $form = new $formClass;

        return $form;
    }
}
