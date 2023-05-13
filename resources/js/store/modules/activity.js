export default {
    namespace: true,
    state: {
      activities: [],
    },
    mutations: {
      setActivities(state, activities) {
        state.activities = activities;
      },
      addActivity(state, activity) {
        state.activities.push(activity);
      },
      updateActivity(state, updatedActivity) {
        const index = state.activities.findIndex(
          (activity) => activity.id === updatedActivity.id
        );
        if (index !== -1) {
          state.activities.splice(index, 1, updatedActivity);
        }
      },
      deleteActivity(state, activityId) {
        state.activities = state.activities.filter(
          (activity) => activity.id !== activityId
        );
      },
    },
    actions: {
      setActivities({ commit }, activities) {
        commit('setActivities', activities);
      },
      addActivity({ commit }, activity) {
        commit('addActivity', activity);
      },
      updateActivity({ commit }, updatedActivity) {
        commit('updateActivity', updatedActivity);
      },
      deleteActivity({ commit }, activityId) {
        commit('deleteActivity', activityId);
      },
    },
    getters: {
      activities: (state) => state.activities,
    },
  };
  