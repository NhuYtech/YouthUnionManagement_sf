// Cài đặt font mặc định giống Bootstrap
Chart.defaults.font.family =
  "Nunito, -apple-system, system-ui, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif";
Chart.defaults.font.color = "#858796";

// Hàm định dạng số
function number_format(number, decimals, dec_point, thousands_sep) {
  number = (number + "").replace(",", "").replace(" ", "");
  var n = !isFinite(+number) ? 0 : +number,
    prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
    sep = thousands_sep === undefined ? "," : thousands_sep,
    dec = dec_point === undefined ? "." : dec_point,
    s = "",
    toFixedFix = function (n, prec) {
      var k = Math.pow(10, prec);
      return "" + Math.round(n * k) / k;
    };
  s = (prec ? toFixedFix(n, prec) : "" + Math.round(n)).split(".");
  if (s[0].length > 3) {
    s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
  }
  if ((s[1] || "").length < prec) {
    s[1] = s[1] || "";
    s[1] += new Array(prec - s[1].length + 1).join("0");
  }
  return s.join(dec);
}

// Lấy phần tử canvas
var ctx = document.getElementById("myBarChart");

if (ctx) {
  // Gọi API để lấy dữ liệu
  fetch("/api/statistics/events-per-month")
    .then((response) => {
      if (!response.ok) {
        throw new Error("Không thể kết nối với API");
      }
      return response.json();
    })
    .then((data) => {
      console.log("Dữ liệu trả về từ API:", data);
      // Khởi tạo mảng dữ liệu cho 12 tháng với giới hạn 50
      const fullData = Array.from({ length: 12 }, (_, i) => {
        const monthData = data.find((item) => item.month === i + 1);
        console.log(`Tháng ${i + 1}:`, monthData ? monthData.count : 0);
        let value = monthData ? Math.min(monthData.count, 50) : 0;
        return Math.max(0, Math.min(value, 50));
      });

      // Tạo labels cho 12 tháng
      const months = Array.from({ length: 12 }, (_, i) => `Tháng ${i + 1}`);

      // Tạo biểu đồ với dữ liệu API

      // Tạo biểu đồ với dữ liệu API
      var myBarChart = new Chart(ctx, {
        type: "bar",
        data: {
          labels: months,
          datasets: [
            {
              label: "Hoạt động",
              backgroundColor: "#4e73df",
              hoverBackgroundColor: "#2e59d9",
              borderColor: "#4e73df",
              data: fullData,
              maxBarThickness: 30,
            },
          ],
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          layout: {
            padding: {
              left: 10,
              right: 25,
              top: 25,
              bottom: 0,
            },
          },
          scales: {
            x: {
              grid: {
                display: false, // Ẩn toàn bộ đường kẻ dọc
              },
              ticks: {
                display: false, // Ẩn các giá trị của trục X nếu không cần thiết
              },
            },
            y: {
              min: 0,
              max: 50,
              ticks: {
                beginAtZero: true,
                precision: 0,
                padding: 10,
              },
              grid: {
                color: "rgb(234, 236, 244)", // Đường kẻ ngang màu nhạt
                zeroLineColor: "rgb(234, 236, 244)",
                drawBorder: false, // Ẩn đường kẻ dọc của trục Y
                borderDash: [2], // Đường kẻ ngang kiểu gạch chấm
                zeroLineBorderDash: [2],
              },
            },
          },
          plugins: {
            legend: {
              display: false,
            },
            tooltip: {
              backgroundColor: "rgb(255,255,255)",
              bodyColor: "#858796",
              borderColor: "#dddfeb",
              borderWidth: 1,
              titleMarginBottom: 10,
              titleColor: "#6e707e",
              titleFont: {
                size: 14,
              },
              padding: {
                x: 15,
                y: 15,
              },
              displayColors: false,
              caretPadding: 10,
              callbacks: {
                label: function (context) {
                  var datasetLabel = context.dataset.label || "";
                  return (
                    datasetLabel +
                    ": " +
                    number_format(context.raw) +
                    " hoạt động"
                  );
                },
              },
            },
          },
        },
      });
      // Ép giá trị trục Y thủ công (nếu cần)
      myBarChart.options.scales.y.min = 0;
      myBarChart.options.scales.y.max = 50;
      myBarChart.update();
    })
    .catch((error) => {
      console.error("Error fetching data:", error);
      // Hiển thị thông báo lỗi trong vùng biểu đồ
      document.querySelector(".chart-bar").innerHTML =
        '<div class="text-center text-danger mt-4 mb-4">Không thể tải dữ liệu biểu đồ. Vui lòng thử lại sau.</div>';
    });
} else {
  // Hiển thị thông báo nếu canvas không tìm thấy
  document.querySelector(".chart-bar").innerHTML =
    '<div class="text-center text-danger mt-4 mb-4">Không tìm thấy phần tử canvas.</div>';
}
