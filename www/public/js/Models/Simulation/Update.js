// TODO - Remove any references of view from this model. Model should not use the view.
// This model is only using the view temporarily to get the portfolio id easily
import * as simulationView from "../../Views/Simulation/SimulationView";
import {api} from "../../Views/Simulation/base";

/**
 * Represents any type of update to a portfolio
 */
export class Update {
    constructor() {}

    /**
     * Get the updated data for a portfolio
     * @returns {Promise<UpdatedPortfolio>}
     */
    static async updatePortfolio() {
        let response = await $.ajax({
            method: 'POST',
            url: `/api/v1/play/${simulationView.getPortfolioId()}/getUpdatedPortfolio`,
            data : {},
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        });

        return new UpdatedPortfolio(response);
    }

    // async getBuyCryptoData() {
    //     return await $.ajax({
    //         method: 'POST',
    //         url: api.getBuyCryptoData,
    //         data : {},
    //         headers: {'Content-Type': 'application/x-www-form-urlencoded'}
    //     });
    // }

    // TODO - Add portfolioId param to constructor to get portfolio id
    async getPortfolio() {
        return await $.ajax({
            method: 'POST',
            url: api.getPortfolio,
            data : {
                'portfolio-id': simulationView.getPortfolioId()
            },
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        });
    }

    // TODO - Add portfolioId param to constructor to get portfolio id
    async saveTransaction(type, transactionAmount, cryptocurrencyId) {
        return await $.ajax({
            method: 'POST',
            url: api.saveTransaction,
            data : {
                'portfolio-id': simulationView.getPortfolioId(),
                'type' : type,
                'transaction-amount': transactionAmount,
                'cryptocurrency-id': cryptocurrencyId
            },
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        });
    }
}

class UpdatedPortfolio {
    constructor(updatePortfolioResponse) {
        this.totalUSDAmount = updatePortfolioResponse.updatedPortfolio.USDAmount;
        this.cryptoWorthInUSD = updatePortfolioResponse.updatedPortfolio.cryptoWorthInUSD;
        this.cryptocurrencies = updatePortfolioResponse.updatedPortfolio.cryptocurrencies;
        this.portfolioID = updatePortfolioResponse.updatedPortfolio.id;
        this.portfolioWorth = updatePortfolioResponse.updatedPortfolio.portfolioWorth;
        this.title = updatePortfolioResponse.updatedPortfolio.title;
        this.portfolioHTML = updatePortfolioResponse.content;
    }
}
