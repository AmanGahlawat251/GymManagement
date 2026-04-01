

(function ($) {
  "use strict";

  function initSelect2($select) {
    if (!$select || !$select.length) return;
    if ($select.hasClass("select2-hidden-accessible")) return; // already initialized

    var $dropdownParent = $select.closest(".offcanvas, .modal");

    $select.select2({
      width: "100%",
      dropdownParent: $dropdownParent.length ? $dropdownParent : undefined
    });
  }

  function initAll() {
    // Standard project classes
    $("select.single-select").each(function () { initSelect2($(this)); });
    $("select.multi-select").each(function () { initSelect2($(this)); });

    // Backward compatibility: treat default-select as single select (many filters use it)
    $("select.default-select").each(function () { initSelect2($(this)); });
  }

  // Initial page load
  $(function () { initAll(); });

  // Re-init for dynamic content (tables/offcanvas content)
  // - On Bootstrap modals/offcanvas: initialize inside to ensure correct dropdownParent.
  $(document).on("shown.bs.modal shown.bs.offcanvas", function (e) {
    initAll();
  });

})(jQuery);