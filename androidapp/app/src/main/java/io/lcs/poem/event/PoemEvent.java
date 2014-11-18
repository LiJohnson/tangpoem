package io.lcs.poem.event;

import android.view.GestureDetector;
import android.view.MotionEvent;

/**
 * Created by john on 2014/11/8.
 */
public class PoemEvent {
	public static abstract class OnSwipe extends GestureDetector.SimpleOnGestureListener {
		private final static int LEN = 100;
		public abstract boolean swipeLeft();
		public abstract boolean swipeRight();

		@Override
		public boolean onFling(MotionEvent motionEvent, MotionEvent motionEvent2, float v, float v2) {
			float dx = (motionEvent2.getX() - motionEvent.getX());
			if( Math.abs( dx ) > LEN ){
				if( dx > 0 ){
					this.swipeRight();
				}else{
					this.swipeLeft();
				}
			}
			return super.onFling( motionEvent , motionEvent2 , v , v2 );
		}
	}
}
