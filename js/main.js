jQuery(document).ready(function ($) {
  /* ==========================================================================
       1. AJAX ПОШУК ТОВАРІВ
       ========================================================================== */
  let searchTimer;
  const searchInput = $(".search-field");
  const searchForm = $(".search-form");

  // Створюємо контейнер для результатів
  searchForm.append(
    '<div class="ajax-search-results" style="display:none;"></div>',
  );
  const resultsContainer = $(".ajax-search-results");

  searchInput.on("keyup", function () {
    let keyword = $(this).val();
    clearTimeout(searchTimer);

    if (keyword.length >= 3) {
      resultsContainer
        .show()
        .html('<div class="ajax-search-loading">Шукаємо... ⏳</div>');
      searchTimer = setTimeout(function () {
        $.ajax({
          // mamba_ajax.url передається через wp_localize_script у functions.php
          url: mamba_ajax.url,
          type: "POST",
          data: {
            action: "mamba_live_search",
            keyword: keyword,
          },
          success: function (data) {
            resultsContainer.html(data);
          },
        });
      }, 500);
    } else {
      resultsContainer.hide().empty();
    }
  });

  // Ховаємо результати при кліку поза полем
  $(document).on("click", function (e) {
    if (!$(e.target).closest(".header-search").length) {
      resultsContainer.hide();
    }
  });

  // Відновлюємо результати при фокусі
  searchInput.on("focus", function () {
    if ($(this).val().length >= 3 && resultsContainer.html() !== "") {
      resultsContainer.show();
    }
  });

  /* ==========================================================================
       2. ВИМКНЕННЯ АВТОЗАПОВНЕННЯ (Агресивний метод)
       ========================================================================== */
  $(".search-form, form").attr("autocomplete", "off");
  $('input[type="search"], input[name="s"], .search-field').each(function () {
    $(this).attr("autocomplete", "off").attr("readonly", "readonly");
    $(this).on("focus", function () {
      $(this).removeAttr("readonly");
    });
  });

  /* ==========================================================================
       3. АВТООНОВЛЕННЯ КОШИКА (WooCommerce)
       ========================================================================== */
  // Делегування подій, оскільки кошик оновлюється через AJAX
  $(document).on("change", "div.woocommerce input.qty", function () {
    $("[name='update_cart']").trigger("click");
  });

  /* ==========================================================================
       4. МОБІЛЬНЕ БУРГЕР-МЕНЮ
       ========================================================================== */
  const headerFlex = $(".mamba-main-header .header-flex");
  if (headerFlex.length) {
    const burgerBtn = $(
      '<button class="mamba-burger"><span></span><span></span><span></span></button>',
    );
    headerFlex.prepend(burgerBtn);

    burgerBtn.on("click", function () {
      $("body").toggleClass("mobile-menu-open");
    });
  }

  /* ==========================================================================
       5. МОБІЛЬНІ ФІЛЬТРИ ТА СОРТУВАННЯ КАТАЛОГУ
       ========================================================================== */
  $("#open-mamba-filters").on("click", function () {
    $(".mamba-shop-sidebar").addClass("active");
    $("#mamba-filters-bg").fadeIn();
    $("body").addClass("no-scroll");
  });

  $("#open-mamba-sorting").on("click", function () {
    $("#mamba-sorting-overlay").addClass("active");
    $("body").addClass("no-scroll");
  });

  $(".close-mamba-overlay, #mamba-filters-bg, #mamba-sorting-overlay").on(
    "click",
    function (e) {
      if (e.target !== this) return;
      $(".mamba-shop-sidebar, #mamba-sorting-overlay").removeClass("active");
      $("#mamba-filters-bg").fadeOut();
      $("body").removeClass("no-scroll");
    },
  );
});
