angular.module('psbSwapp.directive', []).directive('alertMenu', function () {
    return {
        restrict: 'AEC',
        replace: true,
        templateUrl: 'api/views/alert_menu.html.twig',
        scope: true,
        link: function (scope, elem, attrs) {

            scope.$watch('test.alerts', function (alerts) {
                var listAlerts = [];
                if (alerts !== undefined && (scope.showAlerts === "standard")) {
                    for (var i = 0; i < alerts.length; i++) {
                        var found = false;
                        for (var j = 0; j < listAlerts.length; j++) {
                            if (listAlerts[j].name === alerts[i].alert) {
                                listAlerts[j].alerts[listAlerts[j].alerts.length] = alerts[i];
                                found = true;
                                break;
                            }
                        }

                        if (found === false) {
                            listAlerts[listAlerts.length] = {
                                name: alerts[i].alert,
                                alerts: []
                            };
                            listAlerts[listAlerts.length - 1].alerts[listAlerts[listAlerts.length - 1].alerts.length] = alerts[i];
                        }

                    }
                }
                scope.test.filteredAlerts = listAlerts;

                App.reloadMenus();

            });


        }
    };
});

