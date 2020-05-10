<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_users
 *
 * @copyright   Copyright (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JHtml::_('behavior.core');
JHtml::_('formbehavior.chosen', 'select');

$results = $this->getModel("Election")->getResults();

$data = [ ['Office', 'Vote For', 'Vote Against', 'Abstain' ] ] ;

foreach ($results as $n => $row) {
    $data_row = array($row['office'], (int) $row['vote_for'], (int) $row['vote_against'], (int) $row['vote_abstain']);
    
    array_push($data, $data_row);
}

$json_results = json_encode($data);

JFactory::getDocument()->addScript("https://www.gstatic.com/charts/loader.js");

   JFactory::getDocument()->addScriptDeclaration("
        google.charts.load('current', {packages:['corechart']});
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {

            var data = google.visualization.arrayToDataTable($json_results);

            var options = {
                width: 700,
                height: 500,
                legend: { position: 'top', maxLines: 3 },
                bar: { groupWidth: '75%' },
                isStacked: true,
            };
      
            var chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));
            chart.draw(data, options);
        };
    ");

//JFactory::getDocument()->addScriptDeclaration($script);
?>

<legend>Elections Statistics</legend>

<hr>


<h1>2020 AGM - Election Vote Results</h1>

The chart below shows the live results of the 2020 AGM election of officers and Hon Life members.<br>

<div id="chart_div" style="width: 900px; height: 300px;"></div>


