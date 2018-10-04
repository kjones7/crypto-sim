class Update {
    constructor(portfolioId) {
        this.portfolioId = portfolioId;
    }

    async updatePortfolio() {
        const results = await $.ajax({
            method: 'POST',
            url: `/api/v1/play/${this.portfolioId}`,
            data : {},
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        });

        return results;
    }

    async getBuyCryptoData() {
        return await $.ajax({
            method: 'POST',
            url: api.getBuyCryptoData,
            data : {},
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        });
    }

}
