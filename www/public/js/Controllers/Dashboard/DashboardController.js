import {selectors, elements} from "../../Views/Dashboard/base";
import {GroupInvite} from "../../Models/Dashboard/GroupInvite";
import * as DashboardView from "../../Views/Dashboard/DashboardView";

$(document).ready(function() {
    initialize();
});

function initialize() {
    initializeGroupInviteResponseButtonEventListeners();
}

function initializeGroupInviteResponseButtonEventListeners() {
    const groupInvitesWrapper = elements.groupInvitesWrapper;
    const acceptGroupInviteButton = groupInvitesWrapper.querySelector(selectors.acceptGroupInvite);
    const declineGroupInviteButton = groupInvitesWrapper.querySelector(selectors.declineGroupInvite);

    acceptGroupInviteButton.addEventListener('click', async function() {
        const groupId = DashboardView.getGroupIdFromGroupInvite(this);

        const groupInvite = new GroupInvite(groupId);
        const response = await groupInvite.accept();

        if(response.success === true) {
            // Successfully accepted group invite
            DashboardView.removeGroupInvite(this);
        } else {
            // Failure while accepting group invite
            alert("There was an error while processing your request.")
        }
    });

    declineGroupInviteButton.addEventListener('click', async function() {
        const groupId = DashboardView.getGroupIdFromGroupInvite(this);

        const groupInvite = new GroupInvite(groupId);
        const response = await groupInvite.decline();

        if(response.success === true) {
            // Successfully accepted group invite
            DashboardView.removeGroupInvite(this);
        } else {
            // Failure while accepting group invite
            alert("There was an error while processing your request.")
        }
    })
}