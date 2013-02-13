<?php

abstract class ParentReport
{

    const ALIGN_RIGHT = 'text-align-right';
    const ALIGN_LEFT = 'text-align-left';
    const ALIGN_CENTER = 'text-align-center';

    protected $filters;
    protected $data = null;
    protected $totals = null;

    public abstract function getSqlPatter();

    public abstract function getColumns();

    public function __construct(array $filters = array())
    {
        $this->filters = $filters;
    }

    public function hasValidFilters()
    {
        $hasValidFilters = true;
        foreach ($this->getRequiredFilters() as $requiredFilter) {
            if (!array_key_exists($requiredFilter, $this->filters)) {
                $hasValidFilters = false;
                break;
            }
        }

        return $hasValidFilters;
    }

    public function getData()
    {
        if (is_null($this->data) && $this->hasValidFilters()) {
            $this->data = $this->procesQueryFetchAll($this->getSql());

            foreach (array() as $column) {
                foreach ($this->data as $row) {
                    $this->data['currency_codes'][$row['currency_code']] = $row['currency_code'];
                    if (!isset($this->data['total'][$column][$row['currency_code']])) {
                        $this->data['total'][$column][$row['currency_code']] = 0;
                    }
                    $this->data['total'][$column][$row['currency_code']] += $row[$column];
                }
            }
        }
        return $this->data;
    }

    public function getTotals()
    {
        if (is_null($this->totals)) {
            $this->totals = array();
            foreach ($this->getTotalColumns() as $column) {
                foreach ($this->getData() as $row) {
                    $index = isset($row[$this->getTotalRow()]) ? $row[$this->getTotalRow()] : $this->getTotalRow();
                    if (!isset($this->totals[$index][$column])) {
                        $this->totals[$index][$column] = 0;
                        $this->totals[$index][$this->getTotalRow()] = $index;
                    }
                    $this->totals[$index][$column] += $row[$column];
                }
            }
        }

        return $this->totals;
    }

    public function getTotalColumns()
    {
        return array();
    }

    public function getTotalRow()
    {
        return 'total';
    }

    public function hasTotals()
    {
        return !is_null($this->getTotals());
    }

    public function getSql()
    {
        $replacements = $this->getReplacements();
        return str_replace(array_keys($replacements), $replacements, $this->getSqlPatter());
    }

    protected function getReplacements()
    {
        $replacements = array(
            '%order_by%' => $this->getOrderBy(),
            '%where%' => $this->getWhere(),
        );
        $columnKeyPattern = '%%%s%%';
        foreach ($this->getFilters() as $filter => $value) {
            if (!is_array($value)) {
                $replacements[sprintf($columnKeyPattern, $filter)] = $value;
            }
        }

        return $replacements;
    }

    protected function getOrderBy()
    {
        $orderBy = null;
        $orderByColumn = null;
        if (array_key_exists('sort', $this->filters) && $this->filters['sort']) {
            $orderByColumn = $orderBy = $this->filters['sort'];

            if (array_key_exists('sort', $this->filters) && $this->filters['sort_type']) {
                $orderBy .= ' ' . $this->filters['sort_type'];
            }
        }
        if (!in_array($orderByColumn, $this->getColumns())) {
            $orderBy = $this->getDefaultOrderBy();
        }

        return $orderBy;
    }

    protected function getDefaultOrderBy()
    {
        $columns = $this->getColumns();
        return reset($columns);
    }

    protected function getWhere()
    {
        return '';
    }

    public function getColumnHeader($column)
    {
        return $column;
    }

    public function getFormatedValue($row, $column)
    {
        if (!array_key_exists($column, $row)) {
            $formatedValue = null;
        } elseif (in_array($column, $this->getCurrencyColumns())) {
            $formatedValue = my_format_currency($row[$column], $row['currency_code']);
        } elseif (in_array($column, $this->getDateColumns())) {
            $formatedValue = format_date($row[$column], 'D');
        } else {
            $formatedValue = $row[$column];
        }

        return $formatedValue;
    }

    public function getFormatedRowValue($row, $column)
    {
        return $this->getFormatedValue($row, $column);
    }

    public function getDateColumns()
    {
        return array();
    }

    public function getCurrencyColumns()
    {
        return array();
    }

    public function hasTotalColumn($column)
    {
        $totalColumns = $this->getTotalColumns();
        return in_array($column, $totalColumns);
    }

    public function getColumnHeaderClass($column)
    {
        return static::ALIGN_CENTER;
    }

    public function getColumnRowClass($column, array $row = array())
    {
        if (
            in_array($column, $this->getCurrencyColumns()) ||
            in_array($column, $this->getDateColumns())
        ) {
            $class = self::ALIGN_RIGHT;
        } else {
            $class = null;
        }

        return $class;
    }

    /**
     *
     * @return array
     */
    public function getFilters()
    {
        return $this->filters;
    }

    public function getFilter($filterName)
    {
        return isset($this->filters[$filterName]) ? $this->filters[$filterName] : null;
    }

    public function getRequiredFilters()
    {
        return array();
    }

    protected function procesQueryFetchAll($query)
    {
        $connection = Propel::getConnection();
        $statement = $connection->prepare($query);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

}
