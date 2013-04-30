/**
 * A class for converting an element into a slideshow.
 *
 * @note This class depends on the Prototype framework.
 *
 * @note The technique for handling default arguments is based on code from the
 *       Scriptaculous library <http://script.aculo.us/>.
 */
var Slideshow = Class.create(
{
	initialize: function(element)
	{
		this.element = $(element);
		
		var defaults =
		{
			autoSize: true,
			firstSlide: -1,
			slidePeriod: 8.0,
			slides: [],
			transitionPeriod: 0.75,
			transitionResolution: .01
		};
		this.options = Object.extend(defaults, arguments[1] || {});
		
		// set initial slide state
		if (this.options.slides && this.options.firstSlide != -1)
		{
			this.slideIndex = this.options.firstSlide % this.options.slides.length;
			this.currentSlide = this.options.slides[this.slideIndex];
			
			this.element.setStyle(
			{
				backgroundImage: 'url(' + this.currentSlide + ')'
			});
		}
		else
		{
			this.slideIndex = this.options.firstSlide;
			this.currentSlide = null;
		}
		
		// resize target element for slides
		if (this.options.autoSize && this.options.slides)
		{
			var slide = this.currentSlide ? this.currentSlide : this.options.slides[0];
			var image = new Element('img', {src: slide});
			this.element.setStyle(
			{
				width:  image.naturalWidth  + 'px',
				height: image.naturalHeight + 'px'
			});
		}
		
		// create overlay element for transition effects
		this.element.setStyle(
		{
			position: 'relative'
		});
		size = this.element.getDimensions();
		this.transitionOverlay = new Element('div').setStyle(
		{
			display: 'none',
			position: 'absolute',
			
			left:   '0',
			top:    '0',
			width:  size.width  + 'px',
			height: size.height + 'px'
		});
		this.element.insert(this.transitionOverlay);
		
		// start next-slide timer
		var self = this;
		this.nextSlideTimer = new PeriodicalExecuter(function() { self.nextSlide(); }, this.options.slidePeriod);
	},
	
	nextSlide: function()
	{
		if (this.options.slides)
		{
			this.slideIndex = (this.slideIndex + 1) % this.options.slides.length;
			this.currentSlide = this.options.slides[this.slideIndex];
			
			if (this.options.transitionPeriod)
			{
				this.transitionOverlay.setStyle(
				{
					backgroundImage: 'url(' + this.currentSlide + ')',
					display: 'block',
					opacity: '0'
				});
				var self = this;
				this.transitionTimer = new PeriodicalExecuter(function() { self.updateTransition(); }, this.options.transitionResolution);
			}
			else
			{
				this.element.setStyle(
				{
					backgroundImage: 'url(' + this.currentSlide + ')'
				});
			}
		}
	},
	
	updateTransition: function()
	{
		opacity = this.transitionOverlay.getOpacity() + this.options.transitionResolution / this.options.transitionPeriod;;
		if (opacity >= 1.0)
		{
			this.element.setStyle(
			{
				backgroundImage: this.transitionOverlay.getStyle('background-image')
			});
			this.transitionOverlay.hide();
			this.transitionTimer.stop();
		}
		else this.transitionOverlay.setOpacity(opacity);
	}
});
