<?php

namespace App\Libraries\Widgets;

class GridView
{
    const ROWS_PER_PAGE = 50;

    protected $dataProvider;

    protected $columns;

    protected $tools;

    protected $filters;

    public function __construct($dataProvider, $columns)
    {
        $this->dataProvider = $dataProvider;
        $this->columns = $columns;
    }

    public function render()
    {
        echo view('libraries.widgets.gridViews.grid', [
            'dataProvider' => $this->dataProvider,
            'columns' => $this->columns,
            'tools' => $this->tools,
        ]);
    }

    public function setTools($tools)
    {
        $this->tools = $tools;
    }

    public function setFilters($filters)
    {
        $this->filters = $filters;
    }
}