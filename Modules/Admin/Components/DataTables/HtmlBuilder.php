<?php

namespace Modules\Admin\Components\DataTables;

use Collective\Html\HtmlBuilder as BaseHtmlBuilder;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\View\Factory;
use Yajra\DataTables\Html\Builder;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Arr;

class HtmlBuilder extends Builder
{
    /**
     * @param Repository $config
     * @param Factory $view
     * @param \Collective\Html\HtmlBuilder $html
     */
    public function __construct(Repository $config, Factory $view, BaseHtmlBuilder $html)
    {
        $this->setDefaults();

        parent::__construct($config, $view, $html);
    }

    public function setDefaults()
    {
        $this->dom(
            "<'row'"
                ."<'col-sm-12 col-md-6'l>"
                ."<'col-sm-12 col-md-6'f>"
            .">"
            ."<'row'"
                ."<'col-sm-12'"
                    ."<'table-responsive table-hover'tr>"
                .">"
            .">"
            ."<'row'"
                ."<'col-sm-12 order-md-1 order-2 col-md-5'i>"
                ."<'col-sm-12 order-1 col-md-7'"
                    ."<'d-flex justify-content-center justify-content-md-end'p>"
                .">"
            .">"
        );

        $this->attributes['initComplete'] = "function () {
                // Sorting and filtering
                    $('tr.sorter').before($('tr.search-filter'));
                    this.api().columns().every(function (i) {
                        var column = this;
                        var input = document.createElement(\"input\");
                        var sortHeader = column.header();

                        var columnIndex = $(sortHeader).attr('data-col-index');
                        var searchHeader = $(sortHeader).closest('thead').find('.search-filter > th[data-col-index='+columnIndex+']');

                        if(searchHeader.empty().hasClass('searchable')) {
                            $(input).appendTo(searchHeader)
                            .on('change', function () {
                                column.search($(this).val(), false, false, true).draw();
                            });
                        }
                    });

                // Row Selection
                    var table = this.api().table().node();
                    $('#' + table.id + ' tbody').on( 'click', 'tr', function (event) {
                        if($(event.target).is('a, label, :input')) {
                            return true;
                        }

                        $(this).toggleClass('selected');
                        var checkBox = $(this).find('input[type=checkbox]');
                        checkBox.prop('checked', $(this).hasClass('selected') ? 'checked' : '');
                    });

                    $('#' + table.id + ' tbody').on( 'click', 'input[type=checkbox]', function () {
                        if($(this).is(':checked')) {
                            $(this).closest('tr').addClass('selected');
                        } else {
                            $(this).closest('tr').removeClass('selected');
                        }
                    });

                    $('#' + table.id + ' thead').on( 'click', 'input[type=checkbox]', function (event) {
                        var selectAll = $(this).is(':checked');
                        $('#' + table.id + ' tbody tr').each(function (i) {
                            if(selectAll) {
                                $(this).addClass('selected');
                                $(this).find('input[type=checkbox]').prop('checked', 'checked');
                            } else {
                                $(this).removeClass('selected');
                                $(this).find('input[type=checkbox]').prop('checked', '');
                            }
                        });
                    });
            }";
    }

    /**
     * Generate DataTable's table html.
     *
     * @param array $attributes
     * @param bool $drawFooter
     * @param bool $drawSearch
     * @return \Illuminate\Support\HtmlString
     */
    public function table(array $attributes = [], $drawFooter = false, $drawSearch = false)
    {
        $this->setTableAttributes($attributes);

        $htmlAttr = $this->html->attributes($this->tableAttributes);

        $tableHtml = '<table ' . $htmlAttr . '>';

        $sorterHtml = $drawSearch ? '<tr class="sorter">' . implode('', $this->compileTableSorterHeaders()) . '</tr>' : '';
        $searchHtml = $drawSearch ? '<tr class="search-filter">' . implode('', $this->compileTableSearhHeaders()) . '</tr>' : '';

        $tableHtml .= '<thead>'.$searchHtml.$sorterHtml.'</thead>';

        if ($drawFooter) {
            $tf = $this->compileTableFooter();
            $tableHtml .= '<tfoot><tr>' . implode('', $tf) . '</tr></tfoot>';
        }
        $tableHtml .= '</table>';

        return new HtmlString($tableHtml);
    }

    /**
     * Compile table headers and to support responsive extension.
     *
     * @return array
     */
    protected function compileTableSearhHeaders()
    {
        $search = [];
        foreach ($this->collection->all() as $key => $row) {
            $searchable = $row['searchable'] && isset($row->search);
            $label = $searchable ? $row->search : $row['title'];

            $search[] = '<th ' . ($searchable ? 'class="searchable" ' : '') . 'data-col-index="' . $key . '">' . $label . '</th>' ;
        }

        return $search;
    }

    /**
     * Compile table search headers.
     *
     * @return array
     */
    protected function compileTableSorterHeaders()
    {
        $th = [];
        foreach ($this->collection->toArray() as $key => $row) {
            $thAttr = $this->html->attributes(array_merge(
                Arr::only($row, ['class', 'id', 'title', 'width', 'style', 'data-class', 'data-hide']),
                $row['attributes'],
                isset($row['titleAttr']) ? ['title' => $row['titleAttr']] : []
            ));
            $th[] = '<th ' . $thAttr . ' data-col-index="' . $key . '">' . $row['title'] . '</th>';
        }

        return $th;
    }
}

