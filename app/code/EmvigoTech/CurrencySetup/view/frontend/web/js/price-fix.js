require(['jquery'], function ($) {
    function formatDisplayedPrices() {
        $('[class*="price"], .price').each(function () {
            let el = $(this);
            let html = el.html();

            // Match a price pattern like 1,234.00 or 12,345.67
            let match = html.match(/(\d{1,3}),(\d{3})(\.\d{2})?/);
            if (match) {
                // Format the number with apostrophe separator
                let formatted = match[1] + "'" + match[2];

                // Always append .00 regardless of original decimal
                formatted += '.00';

                // Replace only the matched number, not the entire HTML
                let newHtml = html.replace(match[0], formatted);
                if (html !== newHtml) {
                    el.html(newHtml);
                }
            }
        });
    }

    $(document).ready(function () {
        formatDisplayedPrices();
        setInterval(formatDisplayedPrices, 1000);
    });
});
