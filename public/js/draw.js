
const brandName = ['Walmart', 'Amazon', 'TikTok', 'Google Play', 'Coupang', 'Rakuten'];

const ctxBar = document.getElementById('myChartBar');

  new Chart(ctxBar, {
    type: 'bar',
    data: {
      labels: brandName,
      datasets: [{
        label: '# of Votes',
        data: [12, 19, 3, 5, 2, 3],
        borderWidth: 1
      }]
    },
    options: {
      scales: {
        y: {
          beginAtZero: true
        }
      }
    }
  });

  const ctxPie = document.getElementById('myChartPie');

  new Chart(ctxPie, {
    type: 'pie',
    data: {
      labels: brandName,
      datasets: [{
        label: '# of Votes',
        data: [12, 19, 3, 5, 2, 3],
        borderWidth: 1
      }]
    },
    options: {
      scales: {
        y: {
          beginAtZero: true
        }
      }
    }
  });

  


const data = {
  labels: brandName,
  datasets: [
    {
        label: 'Dataset 1',
        data: [10, 30, 39, 20, 25, 34],
        borderColor: Utils.CHART_COLORS.red,
        backgroundColor: Utils.CHART_COLORS.red,
    },
    {
        label: 'Dataset 2',
        data: [18, 33, 22, 19, 11, 39],
        borderColor: Utils.CHART_COLORS.blue,
        backgroundColor: Utils.CHART_COLORS.blue,
    }
]
};

const config = {
    type: 'line',
    data: data,
  };

  const ctxLine = document.getElementById('myChartLine');

  new Chart(ctxLine, {
    type: 'line',
    data: data,
    options: {
      scales: {
        y: {
          beginAtZero: true
        }
      }
    }
  });
