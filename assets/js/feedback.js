/**
 * @global jQuery
 * @global gvFeedback
 */
!function(e,o,n){window.HSCW=o,window.HS=n,n.beacon=n.beacon||{};var t=n.beacon;t.userConfig={},t.readyQueue=[],t.config=function(e){this.userConfig=e},t.ready=function(e){this.readyQueue.push(e)},o.config={docs:{enabled:!0,baseUrl:"http://gravityview.helpscoutdocs.com"},contact:{enabled:!0,formId:"7a2a1309-62db-11e5-8846-0e599dc12a51"}};var r=e.getElementsByTagName("script")[0],c=e.createElement("script");c.type="text/javascript",c.async=!0,c.src="https://djtflbt20bdde.cloudfront.net/",r.parentNode.insertBefore(c,r)}(document,window.HSCW||{},window.HS||{});

HS.beacon.config({
	color: '#4d9bbe',
	poweredBy: false,
	translation: gvBeaconTranslation
});

HS.beacon.ready(function() {
	"use strict";
	HS.beacon.identify( gvBeacon );
});