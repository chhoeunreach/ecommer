(function (window, document, $) {
    "use strict";

    var overlay;
    var pendingRequests = 0;
    var originalNotify = window.AIZ && window.AIZ.plugins
        ? window.AIZ.plugins.notify
        : null;

    function getOverlay() {
        overlay = overlay || document.getElementById("admin-crud-loading");
        return overlay;
    }

    function showLoading(message) {
        var element = getOverlay();
        if (!element) return;

        var text = element.querySelector(".admin-crud-loading__text");
        if (text && message) text.textContent = message;
        element.classList.add("is-visible");
        element.setAttribute("aria-hidden", "false");
        document.body.classList.add("admin-crud-is-loading");
    }

    function hideLoading(force) {
        var element = getOverlay();
        if (!element || (!force && pendingRequests > 0)) return;

        element.classList.remove("is-visible");
        element.setAttribute("aria-hidden", "true");
        document.body.classList.remove("admin-crud-is-loading");
    }

    function beginRequest(message) {
        pendingRequests += 1;
        showLoading(message);
    }

    function endRequest() {
        pendingRequests = Math.max(0, pendingRequests - 1);
        hideLoading(false);
    }

    function isMutation(method) {
        return /^(POST|PUT|PATCH|DELETE)$/i.test(method || "GET");
    }

    function iconFor(level) {
        var icons = {
            danger: "error",
            error: "error",
            warning: "warning",
            info: "info",
            success: "success"
        };

        return icons[level] || "info";
    }

    function notify(level, message) {
        if (!window.Swal) {
            if (typeof originalNotify === "function") {
                return originalNotify.call(window.AIZ.plugins, level, message);
            }
            return;
        }

        return window.Swal.fire({
            toast: true,
            position: "top-end",
            icon: iconFor(level),
            title: message,
            showConfirmButton: false,
            timer: level === "danger" || level === "error" ? 4500 : 3000,
            timerProgressBar: true
        });
    }

    function confirmDelete(url) {
        var options = {
            title: window.AdminCrudMessages.confirmTitle,
            text: window.AdminCrudMessages.confirmDelete,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#f0416c",
            cancelButtonColor: "#6c757d",
            confirmButtonText: window.AdminCrudMessages.yesDelete,
            cancelButtonText: window.AdminCrudMessages.cancel,
            reverseButtons: true,
            focusCancel: true
        };

        if (!window.Swal) {
            if (window.confirm(options.text)) {
                showLoading(window.AdminCrudMessages.deleting);
                window.location.assign(url);
            }
            return;
        }

        window.Swal.fire(options).then(function (result) {
            if (result.isConfirmed) {
                showLoading(window.AdminCrudMessages.deleting);
                window.location.assign(url);
            }
        });
    }

    window.AdminCrudFeedback = {
        show: showLoading,
        hide: function () {
            pendingRequests = 0;
            hideLoading(true);
        },
        notify: notify,
        confirmDelete: confirmDelete
    };

    window.AdminCrudMessages = window.AdminCrudMessages || {
        loading: "Processing...",
        deleting: "Deleting...",
        confirmTitle: "Are you sure?",
        confirmDelete: "This record will be deleted and cannot be recovered.",
        yesDelete: "Yes, delete it",
        cancel: "Cancel"
    };

    // Route every existing admin notification through SweetAlert without
    // requiring each CRUD screen to be changed separately.
    if (window.AIZ && window.AIZ.plugins) {
        window.AIZ.plugins.notify = notify;
    }

    // Intercept the existing delete buttons before the legacy Bootstrap modal.
    document.addEventListener("click", function (event) {
        var trigger = event.target.closest(".confirm-delete");
        if (!trigger) return;

        var url = trigger.getAttribute("data-href");
        if (!url) return;

        event.preventDefault();
        event.stopImmediatePropagation();
        confirmDelete(url);
    }, true);

    // Normal create/update forms navigate away, so the overlay stays visible
    // until the next page is ready. Invalid and cancelled forms are ignored.
    document.addEventListener("submit", function (event) {
        var form = event.target;
        if (!(form instanceof HTMLFormElement) || form.matches("[data-no-loading]")) return;
        if (form.target && form.target.toLowerCase() === "_blank") return;

        window.setTimeout(function () {
            if (!event.defaultPrevented) {
                showLoading(window.AdminCrudMessages.loading);
            }
        }, 0);
    });

    if ($) {
        $(document).ajaxSend(function (_event, _xhr, settings) {
            if (isMutation(settings.type || settings.method)) {
                settings.__adminCrudLoading = true;
                beginRequest(window.AdminCrudMessages.loading);
            }
        });

        $(document).ajaxComplete(function (_event, _xhr, settings) {
            if (settings.__adminCrudLoading) endRequest();
        });
    }

    // Cover CRUD calls implemented with fetch as well as jQuery AJAX.
    if (window.fetch) {
        var originalFetch = window.fetch;
        window.fetch = function (input, init) {
            var method = init && init.method
                ? init.method
                : (input && input.method ? input.method : "GET");
            var track = isMutation(method);

            if (track) beginRequest(window.AdminCrudMessages.loading);
            return originalFetch.apply(this, arguments).then(function (response) {
                if (track) endRequest();
                return response;
            }, function (error) {
                if (track) endRequest();
                throw error;
            });
        };
    }

    window.addEventListener("pageshow", function () {
        pendingRequests = 0;
        hideLoading(true);
    });
})(window, document, window.jQuery);
