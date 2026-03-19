/**
 * ConfidUp — Performance enhancements
 * Loaded with defer, runs after the page is parsed.
 */
( function () {
	'use strict';

	/* ------------------------------------------------------------------
	   Intersection Observer — lazy-load background images
	   For any element with data-bg attribute.
	   ------------------------------------------------------------------ */
	if ( 'IntersectionObserver' in window ) {
		var lazyBgs = document.querySelectorAll( '[data-bg]' );

		if ( lazyBgs.length ) {
			var bgObserver = new IntersectionObserver( function ( entries ) {
				entries.forEach( function ( entry ) {
					if ( entry.isIntersecting ) {
						entry.target.style.backgroundImage = 'url(' + entry.target.dataset.bg + ')';
						entry.target.removeAttribute( 'data-bg' );
						bgObserver.unobserve( entry.target );
					}
				} );
			} );

			lazyBgs.forEach( function ( el ) {
				bgObserver.observe( el );
			} );
		}
	}

	/* ------------------------------------------------------------------
	   Smooth scroll for anchor links (jump navigation on FAQ, legal pages)
	   ------------------------------------------------------------------ */
	document.addEventListener( 'click', function ( e ) {
		var anchor = e.target.closest( 'a[href^="#"]' );
		if ( ! anchor ) return;

		var target = document.getElementById( anchor.getAttribute( 'href' ).slice( 1 ) );
		if ( ! target ) return;

		e.preventDefault();
		target.scrollIntoView( { behavior: 'smooth', block: 'start' } );
	} );

	/* ------------------------------------------------------------------
	   Mark navigation links as active based on current URL
	   ------------------------------------------------------------------ */
	var currentPath = window.location.pathname;
	document.querySelectorAll( '.wp-block-navigation-item__content' ).forEach( function ( link ) {
		if ( link.getAttribute( 'href' ) === currentPath ) {
			link.closest( '.wp-block-navigation-item' ).classList.add( 'current-menu-item' );
		}
	} );

} () );
