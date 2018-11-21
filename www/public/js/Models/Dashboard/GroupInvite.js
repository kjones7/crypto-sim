import {apiRoutes} from "../../Views/Dashboard/base";

export class GroupInvite {
    constructor(groupId) {
        this.groupId = groupId;
    }

    async accept() {
        return await $.ajax({
            method: 'POST',
            url: apiRoutes.acceptGroupInvite,
            data : {
                groupId: this.groupId
            },
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        });
    }

    async decline() {
        return await $.ajax({
            method: 'POST',
            url: apiRoutes.declineGroupInvite,
            data : {
                groupId: this.groupId
            },
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        });
    }
}
