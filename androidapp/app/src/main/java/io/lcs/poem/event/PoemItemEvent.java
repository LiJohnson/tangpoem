package io.lcs.poem.event;

import android.app.Activity;
import android.app.FragmentTransaction;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.widget.AdapterView;

import io.lcs.poem.MainActivity;
import io.lcs.poem.R;
import io.lcs.poem.pojo.Poem;

/**
 * Created by john on 2014/11/8.
 */
public class PoemItemEvent {
	public static class Click  implements AdapterView.OnItemClickListener {

		@Override
		public void onItemClick(AdapterView<?> parent, View view, int position, long id) {
			Poem poem = (Poem)view.getTag();
			Bundle b = new Bundle();
			b.putSerializable("poem", poem);
			MainActivity.PoemFragment pf = new MainActivity.PoemFragment();
			pf.setArguments(b);
			((Activity)view.getContext()).getFragmentManager().beginTransaction()
					.setTransition(FragmentTransaction.TRANSIT_FRAGMENT_FADE)
					.addToBackStack(null)
					.replace(R.id.container, pf)
					.commit();
		}
	}
}
