const renderTransactionWindow  = function(button, cryptoPrice) {
    // Get body of popover
    const popoverBody = document.querySelector('.popover-body');

    // Clear stale contents of popover
    popoverBody.innerHTML = '';

    // Add transaction controls into popover
    popoverBody.innerHTML = `
        <span style="font-size: 20px; font-weight: bold">$ </span><input type="text" name="transaction-amount">
        <div style="margin-top: .5rem" class="transaction-buttons text-center">
            <button class="btn btn-success btn-sm" type="submit" name="crypto-worth" value="${cryptoPrice}">Buy</button>
        </div>
    `;
}