class Transaction {
    constructor(cryptoID, type, usdAmount, cryptoAmount) {
        this.usdAmount = usdAmount;
        this.cryptocurrencyAmount = cryptoAmount;
        this.cryptoID = cryptoID;
        this.type = type;
    }

    // async buy() {
    //     const results = axios({
    //         method : 'POST',
    //         url: '',
    //         data : {
    //             'cryptocurrency-id' : this.cryptoID,
    //             'type' : this.type,
    //             'transaction-amount' : this.usdAmount
    //         }
    //     })
    //     .catch(error => {
    //         console.log(error.message);
    //     });
    //     //TODO - Get total USD amount, crypto worth in USD, portfolio worth, and portfolio entry HTML
    // }
    async saveTransaction() {
        const results = await $.ajax({
            method: 'POST',
            url: '',
            data : {
                'cryptocurrency-id' : this.cryptoID,
                'type' : this.type,
                'transaction-amount' : this.usdAmount
            },
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        });

        return results;
    }
}
