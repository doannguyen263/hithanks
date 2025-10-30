(function ($) { 
  'use strict';

  /**
   * Utility: Format number to VND format
   * @param {number} price 
   * @returns {string}
   */
  function formatVND(price) {
    return price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".") + ' đ';
  }

  /**
   * Order Detail Calculator
   * Handle checkbox selection and calculate totals for order detail boxes
   */
  class OrderDetailCalculator {
    constructor() {
      this.$checkboxes = $('input[name="order_detail_items[]"]');
      this.priceMap = {};
      
      this.init();
    }

    init() {
      // Build price map from existing elements
      this.buildPriceMap();
      
      // Listen checkbox change event for each box
      this.$checkboxes.on('change', (e) => {
        this.calculateBoxTotal($(e.target));
        
        // Notify overview calculator to update
        if (window.overviewCalculator) {
          window.overviewCalculator.calculate();
        }
        
        // Update global form data
        if (typeof updateGlobalFormData === 'function') {
          setTimeout(updateGlobalFormData, 100);
        }
      });
      
      // Calculate initial totals for all boxes
      this.calculateAllBoxTotals();
      
      // Update global form data
      if (typeof updateGlobalFormData === 'function') {
        setTimeout(updateGlobalFormData, 100);
      }
    }

    /**
     * Build price map from DOM
     * Map: { 'order_detail_item_1-1': 500000, 'order_detail_item_1-2': 300000, ... }
     */
    buildPriceMap() {
      $('[class*="js-order-detail-item-price-"]').each((index, el) => {
        const $el = $(el);
        const classes = $el.attr('class').split(' ');
        const priceClass = classes.find(cls => cls.startsWith('js-order-detail-item-price-'));
        
        if (priceClass) {
          const id = priceClass.replace('js-order-detail-item-price-', '');
          const priceText = $el.text().replace(/\./g, '').replace(' đ', '').trim();
          const price = parseInt(priceText) || 0;
          
          // Map with full id (order_detail_item_1-1)
          const fullId = 'order_detail_item_' + id;
          this.priceMap[fullId] = price;
        }
      });
    }

    /**
     * Calculate total for all boxes
     */
    calculateAllBoxTotals() {
      const self = this;
      this.$checkboxes.each(function() {
        self.calculateBoxTotal($(this));
      });
    }

    /**
     * Calculate total price for a specific box
     * @param {jQuery} $checkbox - The checkbox that triggered the calculation
     */
    calculateBoxTotal($checkbox) {
      // Find the box container (go up to find parent .el-box or closest container)
      const $box = $checkbox.closest('.js-page-order');
      
      // Find total price element within this box
      const $totalPriceElement = $box.find('.js-order-detail-total-price').first();
      
      if ($totalPriceElement.length === 0) return;
      
      let total = 0;
      
      // Get all checkboxes within this box
      const $boxCheckboxes = $box.find('input[name="order_detail_items[]"]');
      
      // Sum all checked checkbox prices in this box
      $boxCheckboxes.filter(':checked').each((index, checkbox) => {
        const value = $(checkbox).val();
        
        if (this.priceMap[value]) {
          total += this.priceMap[value];
        }
      });

      // Format and display total
      const formattedTotal = formatVND(total);
      $totalPriceElement.text(formattedTotal);
    }
  }

  /**
   * Overview Calculator
   * Calculate price based on quantity * coefficient * order detail total
   */
  class OverviewCalculator {
    constructor() {
      this.coefficients = {};
      this.roomNames = {}; // Map input name to room name
      this.orderDetailBaseTotal = 0; // Store base total to avoid re-parsing
      
      this.init();
    }

    init() {
      // Store coefficients and map input name to display element
      $('.js-order-detail-overview input[type="number"]').each((index, el) => {
        const $input = $(el);
        const inputName = $input.attr('name'); // e.g., "order_overview_1"
        const coefficient = parseFloat($input.data('coefficient'));
        
        // Extract ID from input name to find display div
        const match = inputName.match(/order_overview_(\d+)/);
        if (match) {
          const id = match[1];
          const displaySelector = `.js-order-overview-${id}`;
          
          if (coefficient && coefficient > 0) {
            this.coefficients[inputName] = coefficient;
            this.roomNames[inputName] = displaySelector; // Store selector instead
          }
        }
      });
      
      // Listen to input changes in overview box
      $('.js-order-detail-overview input[type="number"]').on('input change', (e) => {
        const $input = $(e.target);
        const inputName = $input.attr('name');
        this.calculateItem($input, inputName);
        
        // Update global form data
        if (typeof updateGlobalFormData === 'function') {
          setTimeout(updateGlobalFormData, 100);
        }
      });
      
      // Initial calculation
      this.calculate();
      
      // Update global form data
      if (typeof updateGlobalFormData === 'function') {
        setTimeout(updateGlobalFormData, 100);
      }
    }

    /**
     * Calculate a single overview item
     */
    calculateItem($input, inputName) {
      const quantity = parseFloat($input.val()) || 0;
      const coefficient = this.coefficients[inputName] || 1;
      
      // Use stored base total instead of re-parsing
      const orderDetailTotal = this.orderDetailBaseTotal || 0;
      
      // Calculate: số lượng * hệ số * tổng tiền order detail
      // Use Math.round to avoid floating point errors
      const result = Math.round(quantity * coefficient * orderDetailTotal);
      
      console.info('calculateItem:', {
        quantity,
        coefficient,
        orderDetailTotal,
        calculatedResult: result
      });
      
      // Get display selector
      const displaySelector = this.roomNames[inputName];
      if (displaySelector) {
        // Display result in the div
        const $resultDiv = $(displaySelector);
        if ($resultDiv.length > 0) {
          $resultDiv.text(formatVND(result));
        }
      }
    }

    /**
     * Calculate all overview items
     */
    calculate() {
      // Update base total first to avoid re-parsing errors
      const $orderDetailBox = $('.js-page-order').not('.js-order-detail-overview').first();
      const $orderDetailTotal = $orderDetailBox.find('.js-order-detail-total-price').first();
      
      if ($orderDetailTotal.length > 0) {
        const totalText = $orderDetailTotal.text().replace(/\./g, '').replace(' đ', '').trim();
        this.orderDetailBaseTotal = parseInt(totalText, 10) || 0;
        console.info('orderDetailBaseTotal updated:', this.orderDetailBaseTotal);
      } else {
        this.orderDetailBaseTotal = 0;
      }
      
      const self = this;
      $('.js-order-detail-overview input[type="number"]').each(function() {
        const $input = $(this);
        const inputName = $input.attr('name');
        self.calculateItem($input, inputName);
      });
    }

    /**
     * Get total from order detail box
     */
    getOrderDetailTotal() {
      const $orderDetailBox = $('.js-page-order').not('.js-order-detail-overview').first();
      const $orderDetailTotal = $orderDetailBox.find('.js-order-detail-total-price').first();
      
      if ($orderDetailTotal.length > 0) {
        const totalText = $orderDetailTotal.text().replace(/\./g, '').replace(' đ', '').trim();
        return parseInt(totalText) || 0;
      }
      
      return 0;
    }
  }

  // Global form data container
  window.formData = {
    orderDetail: {
      selectedItems: [],
      totals: {}
    },
    overview: {
      items: []
    },
    grandTotal: 0
  };

  /**
   * Update global form data from both calculators
   */
  function updateGlobalFormData() {
    if (orderCalculator && overviewCalculator) {
      // Update order detail data
      window.formData.orderDetail.selectedItems = [];
      window.formData.orderDetail.totals = {};
      
      // Get all checked checkboxes
      orderCalculator.$checkboxes.filter(':checked').each((index, checkbox) => {
        const value = $(checkbox).val();
        const price = orderCalculator.priceMap[value] || 0;
        
        // Get the label text (name) from the parent label element
        const $checkbox = $(checkbox);
        const $label = $checkbox.closest('label');
        const name = $label.find('span.fw-bold').first().text().trim() || value;
        
        window.formData.orderDetail.selectedItems.push({
          id: value,
          name: name,
          price: price,
          priceFormatted: formatVND(price)
        });
      });

      // Get totals by box
      $('.js-page-order').each((index, box) => {
        const $box = $(box);
        const $totalPriceElement = $box.find('.js-order-detail-total-price').first();
        
        if ($totalPriceElement.length > 0) {
          const totalText = $totalPriceElement.text().replace(/\./g, '').replace(' đ', '').trim();
          const total = parseInt(totalText) || 0;
          window.formData.orderDetail.totals[index] = total;
        }
      });

      // Update overview data
      window.formData.overview.items = [];
      
      $('.js-order-detail-overview input[type="number"]').each(function() {
        const $input = $(this);
        const inputName = $input.attr('name');
        const quantity = parseFloat($input.val()) || 0;
        const coefficient = parseFloat($input.data('coefficient')) || 0;
        
        // Get the calculated result
        const match = inputName.match(/order_overview_(\d+)/);
        if (match) {
          const id = match[1];
          const $resultDiv = $(`.js-order-overview-${id}`);
          let calculatedPrice = 0;
          
          if ($resultDiv.length > 0 && $resultDiv.text() !== '---') {
            const resultText = $resultDiv.text().replace(/\./g, '').replace(' đ', '').trim();
            calculatedPrice = parseInt(resultText) || 0;
          }
          
          window.formData.overview.items.push({
            inputName: inputName,
            id: id,
            quantity: quantity,
            coefficient: coefficient,
            calculatedPrice: calculatedPrice
          });
        }
      });
      
      // Calculate grand total
      // Logic: overview totals already include quantity * coefficient * order detail total
      // So grand total should ONLY be the sum of overview items, NOT order detail totals
      let grandTotal = 0;
      
      // Only add overview totals (which already include order detail calculations)
      window.formData.overview.items.forEach(function(item) {
        grandTotal += item.calculatedPrice;
      });
      
      window.formData.grandTotal = grandTotal;
      
      // Update total display - only update the total in overview box (#order-detail-overview)
      const $totalDisplay = $('#order-detail-overview .js-order-detail-total-price');
      if ($totalDisplay.length > 0) {
        $totalDisplay.text(formatVND(grandTotal));
      }
      
      console.info('Global form data updated:', window.formData);
    }
  }

  // Initialize both calculators on page load
  let orderCalculator = null;
  let overviewCalculator = null;
  
  $(document).ready(function() {
    console.info('Order form script loaded');
    
    // Initialize Order Detail Calculator
    orderCalculator = new OrderDetailCalculator();
    
    // Initialize Overview Calculator
    overviewCalculator = new OverviewCalculator();
    window.overviewCalculator = overviewCalculator;
    
    // Initial form data update
    updateGlobalFormData();

    // Check if form exists
    const $form = $('#order-apartment-form');
    if ($form.length > 0) {
      console.info('Form found, attaching submit handler');
      
      // Helper function to show error message
      function showError($input, message) {
        $input.addClass('error');
        // Remove existing error message
        $input.next('.error-message').remove();
        // Add new error message
        $input.after('<small class="error-message" style="color: #dc3545; display: block; margin-top: 5px;">' + message + '</small>');
      }
      
      function clearError($input) {
        $input.removeClass('error');
        $input.next('.error-message').remove();
      }
      
      // Custom validation function
      function validateForm() {
        let isValid = true;
        const $form = $('#order-apartment-form');
        
        // Clear all previous errors
        $('.error').removeClass('error');
        $('.error-message').remove();
        
        // Validate name
        const $nameInput = $('input[name="your-name"]');
        const name = $nameInput.val().trim();
        if (name.length < 2) {
          isValid = false;
          showError($nameInput, 'Vui lòng nhập tên (tối thiểu 2 ký tự)');
        } else {
          clearError($nameInput);
        }
        
        // Validate phone - Format: starts with 0 and 10 digits, or 11 digits
        const $phoneInput = $('input[name="your-phone"]');
        const phone = $phoneInput.val().trim();
        // Remove all non-digit characters for validation
        const phoneDigits = phone.replace(/\D/g, '');
        
        if (!phone) {
          isValid = false;
          showError($phoneInput, 'Vui lòng nhập số điện thoại');
        } else if (phoneDigits.length < 10) {
          isValid = false;
          showError($phoneInput, 'Số điện thoại phải có ít nhất 10 chữ số');
        } else if (phoneDigits.length > 11) {
          isValid = false;
          showError($phoneInput, 'Số điện thoại tối đa 11 chữ số');
        } else if (phoneDigits.length === 10 && !phoneDigits.startsWith('0')) {
          isValid = false;
          showError($phoneInput, 'Số điện thoại 10 số phải bắt đầu bằng 0');
        } else {
          clearError($phoneInput);
        }
        
        // Validate address
        const $addressInput = $('input[name="your-address"]');
        const address = $addressInput.val().trim();
        if (!address) {
          isValid = false;
          showError($addressInput, 'Vui lòng nhập địa chỉ');
        } else {
          clearError($addressInput);
        }
        
        // Validate area
        const $areaInput = $('input[name="your-dientich"]');
        const area = $areaInput.val().trim();
        if (!area || isNaN(area) || parseFloat(area) <= 0) {
          isValid = false;
          showError($areaInput, 'Vui lòng nhập diện tích hợp lệ (số dương)');
        } else {
          clearError($areaInput);
        }
        
        // Check if at least one checkbox is checked
        const checkedBoxes = $('input[name="order_detail_items[]"]:checked');
        if (checkedBoxes.length === 0) {
          isValid = false;
          console.warn('Please select at least one design detail');
          alert('Vui lòng chọn ít nhất một mục trong Chi tiết thiết kế');
        }
        
        return isValid;
      }
      
      // Format phone number as user types
      $('input[name="your-phone"]').on('input', function() {
        // Allow only numbers
        let value = $(this).val().replace(/\D/g, '');
        $(this).val(value);
      });
      
      // Real-time validation on input blur
      $('input[name="your-phone"]').on('blur', function() {
        const phone = $(this).val().trim();
        const phoneDigits = phone.replace(/\D/g, '');
        
        if (!phone) {
          showError($(this), 'Vui lòng nhập số điện thoại');
        } else if (phoneDigits.length < 10) {
          showError($(this), 'Số điện thoại phải có ít nhất 10 chữ số');
        } else if (phoneDigits.length > 11) {
          showError($(this), 'Số điện thoại tối đa 11 chữ số');
        } else if (phoneDigits.length === 10 && !phoneDigits.startsWith('0')) {
          showError($(this), 'Số điện thoại 10 số phải bắt đầu bằng 0');
        } else {
          clearError($(this));
        }
      });
      
      $('input[name="your-dientich"]').on('blur', function() {
        const area = $(this).val().trim();
        if (!area || isNaN(area) || parseFloat(area) <= 0) {
          showError($(this), 'Vui lòng nhập diện tích hợp lệ (số dương)');
        } else {
          clearError($(this));
        }
      });
      
      // Handle form submission
      $form.on('submit', function(e) {
        e.preventDefault();
        console.info('Form submitted');
        
        // Validate form
        if (!validateForm()) {
          console.info('Form validation failed');
          // Scroll to first error
          const firstError = $('.error').first();
          if (firstError.length) {
            $('html, body').animate({
              scrollTop: firstError.offset().top - 100
            }, 500);
          }
          return false;
        }
        
        console.info('Form validation passed, submitting...');
        
        // Collect all form data
        const formData = collectFormData();
        console.info('Form data collected:', formData);
        
        // Show loading state
        const $button = $form.find('button[type="submit"]');
        const originalText = $button.html();
        $button.prop('disabled', true).html('Đang xử lý...');
        
        // Submit via AJAX
        submitOrderAjax(formData, $button, originalText);
        
        return false;
      });
    } else {
      console.error('Form #order-apartment-form not found!');
    }
  });

  /**
   * Collect all form data
   */
  function collectFormData() {
    const data = {
      action: 'submit_order_form',
      nonce: dntheme_params.dntheme_nonce,
      order_detail: window.formData.orderDetail.selectedItems,
      order_overview: [],
      contact_info: {
        name: $('input[name="your-name"]').val(),
        phone: $('input[name="your-phone"]').val(),
        address: $('input[name="your-address"]').val(),
        address_project: $('input[name="your-address-project"]').val(),
        note: $('textarea[name="your-thongtinthem"]').val(),
        area: $('input[name="your-dientich"]').val()
      },
      totals: {
        order_detail_total: window.formData.orderDetail.totals[0] || 0,
        overview_total: window.formData.grandTotal,
        grand_total: window.formData.grandTotal
      }
    };

    // Get overview items
    $('.js-order-detail-overview input[type="number"]').each(function() {
      const $input = $(this);
      const inputName = $input.attr('name');
      
      const match = inputName.match(/order_overview_(\d+)/);
      if (match) {
        const id = match[1];
        const $resultDiv = $(`.js-order-overview-${id}`);
        let calculatedPrice = 0;
        
        if ($resultDiv.length > 0 && $resultDiv.text() !== '---') {
          const resultText = $resultDiv.text().replace(/\./g, '').replace(' đ', '').trim();
          calculatedPrice = parseInt(resultText) || 0;
        }

        data.order_overview.push({
          name: $input.closest('.row').find('.col-md-3 span').text(),
          quantity: parseFloat($input.val()) || 0,
          coefficient: parseFloat($input.data('coefficient')) || 0,
          price: calculatedPrice
        });
      }
    });

    return data;
  }

  /**
   * Submit order via AJAX
   */
  function submitOrderAjax(data, $button, originalText) {
    console.info('Submitting data:', data);
    
    $.ajax({
      url: dntheme_params.ajax_url,
      type: 'POST',
      dataType: 'json',
      data: {
        action: 'submit_order_form',
        nonce: dntheme_params.dntheme_nonce,
        order_detail: data.order_detail,
        order_overview: data.order_overview,
        contact_info: data.contact_info,
        totals: data.totals
      },
      success: function(response) {
        console.info('AJAX Success:', response);
        
        if (response.success) {
          // Show success message
          alert('Đơn hàng của bạn đã được gửi thành công! Mã đơn hàng: ' + response.data.order_id);
          
          // Optionally redirect or show order details
          if (response.data.redirect_url) {
            window.location.href = response.data.redirect_url;
          }
        } else {
          // Show error message
          alert('Có lỗi xảy ra: ' + (response.data || 'Vui lòng thử lại'));
          $button.prop('disabled', false).html(originalText);
        }
      },
      error: function(xhr, status, error) {
        console.error('AJAX Error:', error);
        console.error('Response text:', xhr.responseText);
        console.error('Status:', xhr.status);
        
        let errorMsg = 'Có lỗi xảy ra khi gửi đơn hàng. Vui lòng thử lại.';
        if (xhr.responseText) {
          try {
            const response = JSON.parse(xhr.responseText);
            errorMsg = response.data || errorMsg;
          } catch(e) {
            console.error('Could not parse error response');
          }
        }
        
        alert(errorMsg);
        $button.prop('disabled', false).html(originalText);
      }
    });
  }

})(jQuery);