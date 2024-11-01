jQuery(document).ready(function($) {
    function transformTable($table) {
        if (window.innerWidth <= 768) {
            if (!$table.next().hasClass('ml-table')) {
                let tableTitle = $table.attr('data-title') || 'Table';
                let $dl = $('<dl class="ml-table"></dl>');
                let $firstHeader = $table.find('thead tr:first').children().first();
                let $headerCells = $table.find('thead tr').children();

                if ($firstHeader.length === 0 && wpapps_tc_table_collapser.allow_no_thead == "1") {
                    $firstHeader = $table.find('tr:first').children().first();
                    $headerCells = $table.find('tr:first').children();
                }

                let firstHeaderText = tableTitle + '<span class="toggle-indicator-mobile"> +</span>';
                $dl.append('<dt>' + firstHeaderText + '</dt>');

                $table.find('tbody tr, tr:not(:first)').each(function() {
                    let $div = $('<div class="ml-row"></div>');
                    let $cells = $(this).find('td');

                    if ($headerCells.length > 0) {
                        $cells.each(function(index) {
                            let headerText = $($headerCells[index]).text().trim().replace(/[\+\-]$/, ''); // Remove any trailing + or - signs
                            if (headerText !== "") {
                                $div.append('<dt class="ml-header-cell">' + headerText + ':</dt>');
                                $div.append('<dd>' + $(this).html() + '</dd>');
                            }
                        });
                    } else {
                        $cells.each(function() {
                            $div.append('<dd>' + $(this).html() + '</dd>');
                        });
                    }

                    $dl.append($div);
                });

                $dl.find('dd').hide();
                $dl.find('dt.ml-header-cell').hide();

                $dl.find('dt:first-child').click(function() {
                    $dl.find('dd, dt.ml-header-cell').toggle();
                    let $indicator = $(this).find('.toggle-indicator-mobile');
                    $indicator.text($dl.find('dd').first().is(':visible') ? ' -' : ' +');
                });

                $table.after($dl);
                $table.hide();
            }
        } else {
            if ($table.next().hasClass('ml-table')) {
                $table.next().remove();
                $table.show();
            }
        }
    }

    $('.collapsible-table').each(function() {
        let $table = $(this);

        let $lastHeaderCell = $table.find('thead tr:first').children().last();
        let $secondRowInHeader = $table.find('thead tr').eq(1);

        if ($lastHeaderCell.length === 0 && wpapps_tc_table_collapser.allow_no_thead == "1") {
            $lastHeaderCell = $table.find('tr:first').children().last();
            $secondRowInHeader = $table.find('tr').eq(1);
        }

        if ($lastHeaderCell.length > 0 && !$lastHeaderCell.hasClass('toggle-indicator')) {
            $lastHeaderCell.append('<span class="toggle-indicator"> ' + (wpapps_tc_table_collapser.default_table_state === 'collapsed' ? '+' : '-') + '</span>');
        }

        if ($table.find('thead').length === 0 && wpapps_tc_table_collapser.allow_no_thead == "1") {
            $table.find('tr:first').show();
            if (wpapps_tc_table_collapser.default_table_state === 'collapsed') {
                $table.find('tr').not(':first').hide();
            } else {
                $table.find('tr').show();
            }
        } else {
            if (wpapps_tc_table_collapser.default_table_state === 'collapsed') {
                $table.find('tbody').hide();
                $secondRowInHeader.hide();
            } else {
                $table.find('tbody').show();
                $secondRowInHeader.show();
            }
        }

        $lastHeaderCell.closest('thead, tr:first-child').on('click', function() {
            let $indicator = $lastHeaderCell.find('.toggle-indicator');
            let $tbody = $table.find('tbody');
            let $targetRows = $table.find('tr').not(':first');

            if ($tbody.length > 0 && $table.find('thead').length > 0) {
                $tbody.toggle();
                $indicator.text($tbody.is(':visible') ? ' -' : ' +');
            } else {
                $targetRows.toggle();
                $indicator.text($targetRows.is(':visible') ? ' -' : ' +');
                $table.find('tr:first').show(); // Ensure the first row remains visible
            }
        });

        if (wpapps_tc_table_collapser.enable_mobile_view == "1") {
            transformTable($table);
        }
    });

    if (wpapps_tc_table_collapser.enable_mobile_view == "1") {
        $(window).resize(function() {
            $('.collapsible-table').each(function() {
                transformTable($(this));
            });
        });
    }
});
