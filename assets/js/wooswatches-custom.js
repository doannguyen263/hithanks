jQuery(document).ready(function($) {
  $('body').on('added_to_cart', function() {
      setTimeout(function() {
          updateSwatchDescriptions();
      }, 1000);
  });
});

function updateSwatchDescriptions() {
  $('.wooswatch-option').each(function() {
      var $option = $(this),
          $label = $option.find('.wooswatch-label'),
          $description = $option.find('.wooswatch-description'),
          $selected = $option.hasClass('selected');
      if ($selected && $description.length) {
          $label.hide();
          $description.show();
      } else {
          $label.show();
          $description.hide();
      }
  });
}
