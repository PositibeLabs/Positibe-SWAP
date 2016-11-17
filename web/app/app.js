(function () {
    'use strict';

    angular.module('psbSwapp', ['ngRoute', 'ngResource', 'ngAnimate', 'psbSwapp.directive'])
        .config(['$locationProvider', '$routeProvider', '$resourceProvider', function ($locationProvider, $routeProvider, $resourceProvider) {
            //$routeProvider
            //    //.when('/', {
            //    //    templateUrl: assetBase + "app/main/main.html",
            //    //    controller: 'MainCtrl'
            //    //})
            //    .otherwise({
            //        redirectTo: '/'
            //    })
            //;

            $locationProvider.html5Mode(true);
            $resourceProvider.defaults.stripTrailingSlashes = true;
        }
        ])
        .controller('TestCtrl', ['$scope', 'Test', function ($scope, Test) {
            $scope.showAlerts = "standard"; //[standard, byUrl]
            $scope.test = new Test();
            $scope.test.status = 'new';
            $scope.test.site = "http://";

            $scope.test.filteredAlerts = [1, 2, 3];

            $scope.test.id = 37;
            $scope.test.status = 'testing';

            //$scope.test.$get();
            $scope.test.isNew = function () {
                return $scope.test.status == 'new';
            };


            $scope.test.isRunning = function () {
                return $scope.test.status == 'running';
            };
            $scope.attack = function () {
                if ($scope.test.isNew() && $scope.rapid_attack.$valid) {
                    console.log('comenzando el test');
                    $scope.test.$save(function (response) {
                        $scope.test.id = response.id;
                        $scope.test.session = response.session;
                        $scope.test.site = response.site;
                        $scope.test.spiders = response.spiders;
                        $scope.test.start_date = response.start_date;
                        $scope.test.currentSpiderScan = response.currentSpiderScan;
                        $scope.test.status = response.status;

                        console.log('el test está corriendo, se debe actualizar la información cada 2 segundo');
                        console.log($scope.test);
                        setTimeout(2000);
                        $scope.updateSpiderScanStatus();
                    });
                }
                else
                    console.log('Unable to save. Validation error!');

                //RapidAttck.attack($scope.url);

            };
            $scope.updateSpiderScanStatus = function () {
                console.log('actualizando el test');
                $scope.test.$getSpiderScanStatus(function (response) {
                    console.log(response);
                    $scope.test.id = response.id;
                    $scope.test.session = response.session;
                    $scope.test.site = response.site;
                    $scope.test.spiders = response.spiders;
                    $scope.test.start_date = response.start_date;
                    $scope.test.currentSpiderScan = response.currentSpiderScan;
                    $scope.test.status = response.status;

                    if ($scope.test.spiders[$scope.test.currentSpiderScan] < 100) {
                        setTimeout(2000);
                        $scope.updateSpiderScanStatus();
                    } else {
                        $scope.test.$runAscan(function (response) {
                            $scope.test.id = response.id;
                            $scope.test.session = response.session;
                            $scope.test.site = response.site;
                            $scope.test.spiders = response.spiders;
                            $scope.test.start_date = response.start_date;
                            $scope.test.currentSpiderScan = response.currentSpiderScan;
                            $scope.test.status = response.status;

                            console.log('el active scan está corriendo, se debe actualizar la información cada 2 segundo');
                            console.log($scope.test);
                            setTimeout(2000);
                            $scope.updateAscanStatus();
                        });
                    }

                })
            };
            $scope.updateAscanStatus = function () {
                console.log('actualizando el estado del escaneo');
                $scope.test.$getAscanStatus(function (response) {
                    console.log(response);
                    $scope.test.id = response.id;
                    $scope.test.session = response.session;
                    $scope.test.site = response.site;
                    $scope.test.spiders = response.spiders;
                    $scope.test.start_date = response.start_date;
                    $scope.test.currentSpiderScan = response.currentSpiderScan;
                    $scope.test.status = response.status;

                    if ($scope.test.spiders[$scope.test.currentSpiderScan] < 100) {
                        setTimeout(2000);
                        $scope.updateSpiderScanStatus();
                    } else {
                        console.log('termino el escaneo, se debe mostrar los resultados');
                        $scope.reloadDatas();
                    }

                })
            },
                $scope.reloadDatas = function () {
                    $scope.test.$reloadDatas(function (response) {
                        $scope.test.id = response.id;
                        $scope.test.session = response.session;
                        $scope.test.site = response.site;
                        $scope.test.spiders = response.spiders;
                        $scope.test.start_date = response.start_date;
                        $scope.test.currentSpiderScan = response.currentSpiderScan;
                        $scope.test.status = response.status;
                        $scope.test.alerts = response.alerts;
                        $scope.test.messages = response.messages;
                        $scope.test.urls = response.urls;
                        $scope.test.conexts = response.contexts;
                        $scope.test.sites = response.sites;
                        console.log(response);
                        updateTrees($scope.test);
                    })
                },
                $scope.loadTest = function () {
                    console.log('cargar test ' + $scope.test.id);
                    $scope.test.$get(function (response) {
                        //$scope.test.id = response.id;
                        //$scope.test.session = response.session;
                        //$scope.test.site = response.site;
                        //$scope.test.spiders = response.spiders;
                        //$scope.test.start_date = response.start_date;
                        //$scope.test.currentSpiderScan = response.currentSpiderScan;
                        //$scope.test.status = response.status;
                        //$scope.test.alerts = response.alerts;
                        //$scope.test.messages = response.messages;
                        //$scope.test.urls = response.urls;
                        //$scope.test.conexts = response.contexts;
                        //$scope.test.sites = response.sites;

                        $scope.test = response;
                        //$scope.test;
                        console.log(response);
                    })
                }
        }])
        .factory('Test', ['$resource', function ($resource) {
            return $resource('api/test/:id', {id: '@id'}, {
                'query': {method: 'GET', isArray: false},
                'getSpiderScanStatus': {
                    url: 'api/test/:id/spider_scan_status',
                    params: {
                        id: '@id',
                        scan: '@currentSpiderScan',
                    }
                },
                'runAscan': {
                    url: 'api/test/:id/run_ascan',
                    params: {
                        id: '@id',
                        scan: '@currentAscan',
                    }
                },
                'getAscanStatus': {
                    url: 'api/test/:id/ascan_status',
                    params: {
                        id: '@id',
                        scan: '@currentAscan',
                    }
                },
                'reloadDatas': {
                    url: 'api/test/:id/reload_datas',
                    params: {
                        id: '@id'
                    }
                },

            });
        }
        ])
    ;
})
();