// TODO - Remove any references of view from this model. Model should not use the view.
// This model is only using the view temporarily to get the portfolio id easily
import * as simulationView from "../../Views/Simulation/SimulationView";
import {api} from "../../Views/Simulation/base";

export class Update {
    constructor() {}

    async updatePortfolio() {
        return await $.ajax({
            method: 'POST',
            url: `/api/v1/play/${simulationView.getPortfolioId()}/getUpdatedPortfolio`,
            data : {},
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        });
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
