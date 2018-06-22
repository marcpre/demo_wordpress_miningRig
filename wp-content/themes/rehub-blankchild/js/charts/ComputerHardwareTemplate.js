jQuery(document).ready(function ($) {

  let postId = $("#miningProfChart").attr('class')

  $.getJSON(miningRigData.root_url + '/wp-json/miningProf/v1/getProfitability?post_id=' + postId, (results) => {
    console.log(results)

    //transform data set
    let dataSet = results.profitabilityCompHardware.map(
      ({
        daily_netProfit,
        daily_grossProfit,
        daily_costs,
        created_at: day
      }) =>
      ({
        daily_netProfit,
        daily_grossProfit,
        daily_costs,
        day
      }))

    new Morris.Line({
      // ID of the element in which to draw the chart.
      element: 'miningProfChart',
      data: dataSet,
      // The name of the data record attribute that contains x-values.
      xkey: 'day',
      // A list of names of data record attributes that contain y-values.
      ykeys: ['daily_netProfit', 'daily_grossProfit', 'daily_costs'],
      // Labels for the ykeys -- will be displayed when you hover over the
      // chart.
      labels: ['Daily Net Profit', 'Daily Gross Profit', 'Daily Costs'],
      lineColors: ['blue', 'green', 'red' ],
      hideHover: 'auto'
    });
  })
});
