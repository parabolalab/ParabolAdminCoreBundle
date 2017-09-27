<?php

namespace Parabol\AdminCoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;


class DashboardController extends Controller
{
    public function indexAction()
    {
    	if($url = $this->container->getParameter('parabol_admin_core.dashboard.redirected'))
    	{
    		return $this->redirectToRoute($url);
    	}
    	return $this->render('ParabolAdminCoreBundle:Dashboard:index.html.twig', array(
			'googleDashboard' => (boolean)$this->container->getParameter('google.view_id')				
        ));    
    }

    public function dataAction($widget)
    {
    	$method = $widget.'Data';

    	$data = array();
    	$data['data'] = $this->$method();
    	$data['widget'] = $widget; 
    	
    	return new JsonResponse($data);
    }


    private function totalActivityData()
    {
    	$this->get('parabol.google_api.analitics')->setScopes(array(\Google_Service_Analytics::ANALYTICS_READONLY));


	    $total = $this->get('parabol.google_api.analitics')->getResults(
    		date('Y-01-01'),
	        'today',
	        'ga:bounceRate,ga:users,ga:sessions,ga:avgSessionDuration,ga:pageviewsPerSession'
	    );
    	
	    $totalsForAllResults = $total->totalsForAllResults;
	    // {{ total['ga:avgSessionDuration'] / 60 > 0 ? (total['ga:avgSessionDuration'] / 60) | number_format ~ 'm' : '' }} {{ ((total['ga:avgSessionDuration']  | round) % 60) | number_format ~ 's' }} 
	    // {{ total['ga:bounceRate'] | round(1) }}
	    // {{ total['ga:users'] }}
	    // {{ total['ga:pageviewsPerSession'] | round(1) }}

	    return array(

	    	'avgSessionDuration' => ($totalsForAllResults['ga:avgSessionDuration'] / 60 > 0 ? (int)($totalsForAllResults['ga:avgSessionDuration'] / 60) . 'm ' : '') . ((int)(round($totalsForAllResults['ga:avgSessionDuration']) % 60)) . 's',
	    	'bounceRate' => (round($totalsForAllResults['ga:bounceRate'] * 10) / 10) . '<sup style="font-size: 20px">%</sup>',
	    	'users' => $totalsForAllResults['ga:users'],
	    	'pageviewsPerSession' => round($totalsForAllResults['ga:pageviewsPerSession'] * 10) / 10
	    );

	   //  return $this->render('ParabolAdminCoreBundle:Dashboard:_totalActivity.html.twig', array(
				
				// 'total' => $total->totalsForAllResults, 

		


    //         ));    
    }


    private function topSitesActivityData()
    {
    	$this->get('parabol.google_api.analitics')->setScopes(array(\Google_Service_Analytics::ANALYTICS_READONLY));

        $topSites = $this->get('parabol.google_api.analitics')->getResults(
    		date('Y-01-01'),
	        'today',
	       'ga:pageviews',
	       array('dimensions' => 'ga:pagePath,ga:pageTitle', 'sort' => '-ga:pageviews', 'max-results' => 10)
	    );

	    return array(
			
				'topSites' => $topSites->rows, 
				
				// 'total' => $total->totalsForAllResults, 

		
            );    
    }

 	
 	private function topPostsActivityUniqueData()
    {
    	$this->get('parabol.google_api.analitics')->setScopes(array(\Google_Service_Analytics::ANALYTICS_READONLY));

        $topSites = $this->get('parabol.google_api.analitics')->getResults(
    		date('Y-01-01'),
	        'today',
	       'ga:uniquePageviews',
	       array('dimensions' => 'ga:pagePath,ga:pageTitle', 'sort' => '-ga:uniquePageviews', 'max-results' => 10)
	    );

	    return array(
			
				'topSites' => $topSites->rows, 
				
				// 'total' => $total->totalsForAllResults, 

		
            );    
    }

    

 

    private function weekActivityData()
    {
    	$this->get('parabol.google_api.analitics')->setScopes(array(\Google_Service_Analytics::ANALYTICS_READONLY));

    	$siteActivitiesWeekBeforeLastWeek = $this->get('parabol.google_api.analitics')->getResults(
    		date('Y-m-d', strtotime('last monday -14 days')),
	        date('Y-m-d', strtotime('last monday -8 days')),
	       'ga:sessions',
	       array('dimensions' => 'ga:nthDay')
	    );

    	$siteActivitiesLastWeek = $this->get('parabol.google_api.analitics')->getResults(
    		date('Y-m-d', strtotime('last monday -7 days')),
	        date('Y-m-d', strtotime('last monday -1 days')),
	       'ga:sessions',
	       array('dimensions' => 'ga:nthDay')
	    );

        $siteActivitiesThisWeek = $this->get('parabol.google_api.analitics')->getResults(
    		date('Y-m-d', strtotime('last monday')),
	        date('Y-m-d', strtotime('last monday +6 days')),
	       'ga:sessions',
	       array('dimensions' => 'ga:nthDay')
	    );

	    $lastWeek = $this->get('parabol.google_api.analitics')->getResults(
    		date('Y-m-d', strtotime('last monday -7 days')),
	        date('Y-m-d', strtotime('last monday -1 days')),
	        'ga:bounceRate,ga:users,ga:sessions,ga:avgSessionDuration'
	    );

	    $thisWeek = $this->get('parabol.google_api.analitics')->getResults(
    		date('Y-m-d', strtotime('last monday')),
	        date('Y-m-d', strtotime('last monday +6 days')),
	        'ga:bounceRate,ga:users,ga:sessions,ga:avgSessionDuration'
	    );

	    $total = $this->get('parabol.google_api.analitics')->getResults(
    		date('Y-01-01'),
	        'today',
	        'ga:avgSessionDuration'
	    );


	    return  array(
	    		'siteActivitiesWeekBeforeLastWeek' => $siteActivitiesWeekBeforeLastWeek->rows, 
				'siteActivitiesLastWeek'  => $siteActivitiesLastWeek->rows,
				'siteActivitiesThisWeek' => $siteActivitiesThisWeek->rows, 
				'_thisWeek' => array(
						'bounceRate' => array('value' => $thisWeek->totalsForAllResults['ga:bounceRate'], 'max' => 100),
						'users' => array('value' => $thisWeek->totalsForAllResults['ga:users'], 'max' => $thisWeek->totalsForAllResults['ga:users'] + $lastWeek->totalsForAllResults['ga:users']),
						'avgSessionDuration' => array('value' => $thisWeek->totalsForAllResults['ga:avgSessionDuration'], 'max' => $total->totalsForAllResults['ga:avgSessionDuration'] > $thisWeek->totalsForAllResults['ga:avgSessionDuration'] ? $total->totalsForAllResults['ga:avgSessionDuration'] : $thisWeek->totalsForAllResults['ga:avgSessionDuration']),
						
					), 
				'_lastWeek' => array(
						'bounceRate' => array('value' => $lastWeek->totalsForAllResults['ga:bounceRate'], 'max' => 100),
						'avgSessionDuration' => array('value' => $lastWeek->totalsForAllResults['ga:avgSessionDuration'], 'max' => $total->totalsForAllResults['ga:avgSessionDuration'] > $lastWeek->totalsForAllResults['ga:avgSessionDuration'] ? $total->totalsForAllResults['ga:avgSessionDuration'] : $lastWeek->totalsForAllResults['ga:avgSessionDuration']),
						'users' => array('value' => $lastWeek->totalsForAllResults['ga:users'], 'max' => $thisWeek->totalsForAllResults['ga:users'] + $lastWeek->totalsForAllResults['ga:users']),
						
					),
	
            );    
    }

    private function  yearActivityData()
    {
    	$this->get('parabol.google_api.analitics')->setScopes(array(\Google_Service_Analytics::ANALYTICS_READONLY));

    	$siteActivitiesLastYear = $this->get('parabol.google_api.analitics')->getResults(
    		date('Y-01-01', strtotime('-1 year')),
	        date('Y-12-31', strtotime('-1 year')),
	       'ga:sessions',
	       array('dimensions' => 'ga:nthMonth')
	    );

	    $siteActivitiesThisYear = $this->get('parabol.google_api.analitics')->getResults(
    		date('Y-01-01'),
	        date('Y-12-31'),
	       'ga:sessions',
	       array('dimensions' => 'ga:nthMonth')
	    );

	    $lastYear = $this->get('parabol.google_api.analitics')->getResults(
    		date('Y-01-01', strtotime('-1 year')),
	        date('Y-12-31', strtotime('-1 year')),
	        'ga:bounceRate,ga:users,ga:sessions,ga:avgSessionDuration'
	    );

	    $thisYear = $this->get('parabol.google_api.analitics')->getResults(
    		date('Y-01-01'),
	        date('Y-12-31'),
	        'ga:bounceRate,ga:users,ga:sessions,ga:avgSessionDuration'
	    );

	    $total = $this->get('parabol.google_api.analitics')->getResults(
    		date('Y-01-01'),
	        'today',
	        'ga:avgSessionDuration'
	    );



	    return array(
				'siteActivitiesLastYear'  => $siteActivitiesLastYear->rows,
				'siteActivitiesThisYear' => $siteActivitiesThisYear->rows, 
				'_thisYear' => array(
						'bounceRate' => array('value' => $thisYear->totalsForAllResults['ga:bounceRate'], 'max' => 100),
						'users' => array('value' => $thisYear->totalsForAllResults['ga:users'], 'max' => $thisYear->totalsForAllResults['ga:users'] + $lastYear->totalsForAllResults['ga:users']),
						'avgSessionDuration' => array('value' => (int)$thisYear->totalsForAllResults['ga:avgSessionDuration'], 'max' => $total->totalsForAllResults['ga:avgSessionDuration'] > $thisYear->totalsForAllResults['ga:avgSessionDuration'] ? $total->totalsForAllResults['ga:avgSessionDuration'] : $thisYear->totalsForAllResults['ga:avgSessionDuration']),
					), 
				'_lastYear' => array(
						'bounceRate' => array('value' => $lastYear->totalsForAllResults['ga:bounceRate'], 'max' => 100),
						'users' => array('value' => $lastYear->totalsForAllResults['ga:users'], 'max' => $thisYear->totalsForAllResults['ga:users'] + $lastYear->totalsForAllResults['ga:users']),
						'avgSessionDuration' => array('value' => (int)$lastYear->totalsForAllResults['ga:avgSessionDuration'], 'max' => $total->totalsForAllResults['ga:avgSessionDuration'] > $lastYear->totalsForAllResults['ga:avgSessionDuration'] ? $total->totalsForAllResults['ga:avgSessionDuration'] : $lastYear->totalsForAllResults['ga:avgSessionDuration']),
					),
				
            );    
    }



}
