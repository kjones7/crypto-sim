// TODO - Remove any references of view from this model. Model should not use the view.
// This model is only using the view temporarily to get the portfolio id easily
import * as simulationView from "../../Views/Simulation/SimulationView";
import {api} from "../../Views/Simulation/base";

export class Leaderboard {
    constructor() {}

    async getLeaderboardData() {
        return await $.ajax({
            method: 'POST',
            url: `/api/v1/play/${simulationView.getPortfolioId()}/getLeaderboard`,
            data : {},
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        });
    }

    async getGroupLeaderboardData(groupId) {
        return await $.ajax({
            method: 'POST',
            url: `/api/v1/play/${simulationView.getPortfolioId()}/getGroupLeaderboard`,
            data : {
                groupId: groupId
            },
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        });
    }
}
