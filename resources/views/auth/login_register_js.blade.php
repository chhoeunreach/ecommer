<script src="{{ static_asset('assets/js/vendors.js') }}"></script>
<script>
    (function ($) {
        // USE STRICT
        "use strict";

        AIZ.data = {
            csrf: $('meta[name="csrf-token"]').attr("content"),
            appUrl: $('meta[name="app-url"]').attr("content"),
            fileBaseUrl: $('meta[name="file-base-url"]').attr("content"),
        };
        AIZ.plugins = {
            notify: function (type = "dark", message = "") {
                var swalType = 'info';
                if (type === 'success') {
                    swalType = 'success';
                } else if (type === 'danger' || type === 'error') {
                    swalType = 'error';
                } else if (type === 'warning') {
                    swalType = 'warning';
                }

                if (typeof Swal !== 'undefined') {
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3500,
                        timerProgressBar: true,
                        didOpen: function (toast) {
                            toast.addEventListener('mouseenter', Swal.stopTimer);
                            toast.addEventListener('mouseleave', Swal.resumeTimer);
                        },
                        customClass: {
                            popup: 'swal2-modern-toast'
                        }
                    });

                    Toast.fire({
                        icon: swalType,
                        title: message
                    });
                } else if (typeof $.notify === 'function') {
                    $.notify({ message: message }, { type: type, delay: 3500, placement: { from: "top", align: "right" } });
                }
            }
        };

    })(jQuery);
</script>
<script>
    @foreach (session('flash_notification', collect())->toArray() as $message)
        AIZ.plugins.notify('{{ $message['level'] }}', '{{ $message['message'] }}');
    @endforeach

    @if (isset($errors) && $errors->any())
        @foreach ($errors->all() as $error)
            AIZ.plugins.notify('danger', '{{ $error }}');
        @endforeach
    @endif

    $('.password-toggle').click(function(){
        var $this = $(this);
        if ($this.siblings('input').attr('type') == 'password') {
            $this.siblings('input').attr('type', 'text');
            $this.removeClass('la-eye').addClass('la-eye-slash');
        } else {
            $this.siblings('input').attr('type', 'password');
            $this.removeClass('la-eye-slash').addClass('la-eye');
        }
    });
</script>

@if (addon_is_activated('otp_system'))
    <script type="text/javascript">
        // Country Code
        var isPhoneShown = true,
            countryData = window.intlTelInputGlobals.getCountryData();

        for (var i = 0; i < countryData.length; i++) {
            var country = countryData[i];
            if (country.iso2 == 'bd') {
                country.dialCode = '88';
            }
        }

        var itiConfig = {
            separateDialCode: true,
            utilsScript: "{{ static_asset('assets/js/intlTelutils.js') }}?1590403638580",
            onlyCountries: @php echo get_active_countries()->pluck('code') @endphp,
            customPlaceholder: function(selectedCountryPlaceholder, selectedCountryData) {
                if (selectedCountryData.iso2 == 'bd') {
                    return "01xxxxxxxxx";
                }
                return selectedCountryPlaceholder;
            }
        };

        // Phone-only inputs (forgot password, phone verification pages)
        var phoneCode = document.querySelector('#phone-code');
        if (phoneCode) {
            var itiPhone = intlTelInput(phoneCode, itiConfig);
            var country = itiPhone.getSelectedCountryData();
            $('input[name=country_code]').val(country.dialCode);

            phoneCode.addEventListener("countrychange", function(e) {
                $('input[name=country_code]').val(itiPhone.getSelectedCountryData().dialCode);
            });
        }

        // Country code dropdown for the combined registration field
        var countrySelect = document.querySelector('#country-code-select');
        if (countrySelect) {
            var options = '<option value="">{{ translate('Country') }}</option>';
            for (var i = 0; i < countryData.length; i++) {
                var c = countryData[i];
                options += '<option value="' + c.dialCode + '">' + c.name + ' (+' + c.dialCode + ')</option>';
            }
            countrySelect.innerHTML = options;
            if (typeof $(countrySelect).selectpicker === 'function') {
                try {
                    $(countrySelect).selectpicker('destroy');
                } catch(err){}
            }
            if ($(countrySelect).parent().hasClass('bootstrap-select')) {
                $(countrySelect).siblings('.dropdown-toggle, .dropdown-menu').remove();
                $(countrySelect).unwrap();
            }
        }

        function toggleLoginPassOTP() {
            $('.password-login-block').addClass('d-none');
            $('.submit-button').html('{{ translate('Login With OTP') }}');

            var url = '{{ route('send-otp') }}';
            $('.loginForm').attr('action', url);
        }

        function toggleEmailPhone(el) {
            if (isPhoneShown) {
                $('.phone-form-group').addClass('d-none');
                $('.email-form-group').removeClass('d-none');
                $('input[name=phone]').val(null);
                isPhoneShown = false;
                $(el).html('*{{ translate('Use Phone Number Instead') }}');
            } else {
                $('.phone-form-group').removeClass('d-none');
                $('.email-form-group').addClass('d-none');
                $('input[name=email]').val(null);
                isPhoneShown = true;
                $(el).html('<i>*{{ translate('Use Email Instead') }}</i>');
            }
        }
    </script> 
@endif

<script>
    function showError(input, message) {
        const formGroup = input.closest('.form-group');
        $(formGroup).find('.invalid-feedback').remove(); 
        $(input).removeClass('is-valid').addClass('is-invalid');
        $(formGroup).append(`<div class="invalid-feedback d-block text-left">${message}</div>`);
    }


    document.addEventListener("input", function(e) {
        const input = e.target;
        if (input.hasAttribute("phone-number")) {
            const formGroup = input.closest('.form-group');
            const original = input.value;
            const numeric = original.replace(/[^0-9]/g, "");

            // Update input
            input.value = numeric;
            // Remove old errors
            $(formGroup).find('.invalid-feedback').remove();
            $(input).removeClass('is-invalid');

            // Show error if original != numeric
            if (original !== numeric) {
                $(input).addClass('is-invalid');
                $(formGroup).append(`<div class="invalid-feedback d-block text-left">
                    {{ translate('Please enter a valid phone number format') }}
                </div>`);
            }
        }
    });

</script>