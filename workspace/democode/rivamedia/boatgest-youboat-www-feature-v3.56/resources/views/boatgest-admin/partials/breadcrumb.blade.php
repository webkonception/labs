
            <ol class="breadcrumb breadcrumb-arrow">
                <li>{!! htmlspecialchars_decode(link_to(
                    url(config('quickadmin.homeRoute')),
                    'Dashboard',
                    []
                )) !!}</li>
                <li class="active"><span>
                    {{ ucfirst(
                        preg_replace(
                            '/([a-z0-9])?([A-Z])/',
                            '$1 $2',
                            str_replace(
                                ['Controller', 'BodCaracts', 'AdsCaracts'],
                                ['', 'Boat On Demand', ucfirst(trans('metas.ads'))],
                                $currentController
                            )
                        )
                    ) }}
                    &nbsp;:&nbsp;
                    {{ ucfirst(
                        preg_replace(
                            '/([a-z0-9])?([A-Z])/',
                            '$1 $2', str_replace(
                                ['Controller', 'index', 'edit', 'create', 'show'],
                                [
                                    '',
                                    'Listing',
                                    'Edit',
                                    'Create',
                                    'Detail'
                                ],
                                $currentAction
                            )
                        )
                    ) }}
                </span></li>
            </ol>

