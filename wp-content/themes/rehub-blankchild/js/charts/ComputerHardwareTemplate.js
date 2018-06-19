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

    console.log(dataSet)

    new Morris.Line({
      // ID of the element in which to draw the chart.
      element: 'miningProfChart',
      // Chart data records -- each entry in this array corresponds to a point on
      // the chart.
      data: dataSet,
      /*[{
                day: '2008',
                value: 20
              },
              {
                day: '2009',
                value: 10
              },
              {
                day: '2010',
                value: 5
              },
              {
                day: '2011',
                value: 5
              },
              {
                day: '2012',
                value: 20
              }
            ],*/
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
