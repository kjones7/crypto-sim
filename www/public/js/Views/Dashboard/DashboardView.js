import {selectors} from "../../Views/Dashboard/base";

export const getGroupIdFromGroupInvite = function(groupInviteButton) {
    return groupInviteButton.dataset['groupid'];
};

export const removeGroupInvite = function(groupInviteButton) {
    var groupInviteElement = groupInviteButton.closest(selectors.groupInviteElement);
    groupInviteElement.parentElement.removeChild(groupInviteElement);
};