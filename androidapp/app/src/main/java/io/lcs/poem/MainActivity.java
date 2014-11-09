package io.lcs.poem;

import android.app.Activity;
import android.app.Fragment;
import android.os.Bundle;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.Menu;
import android.view.MenuItem;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ArrayAdapter;
import android.widget.GridView;
import android.widget.ListView;
import android.widget.TextView;

import io.lcs.poem.adapter.PoemListAdapter;
import io.lcs.poem.event.PoemItemEvent;
import io.lcs.poem.pojo.Poem;


public class MainActivity extends Activity {

	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_main);
		if (savedInstanceState == null) {
			getFragmentManager().beginTransaction()
					.add(R.id.container, new PlaceholderFragment())
					.commit();
		}
	}

	@Override
	public boolean onCreateOptionsMenu(Menu menu) {
		// Inflate the menu; this adds items to the action bar if it is present.
		getMenuInflater().inflate(R.menu.menu_main, menu);
		return true;
	}

	@Override
	public boolean onOptionsItemSelected(MenuItem item) {
		// Handle action bar item clicks here. The action bar will
		// automatically handle clicks on the Home/Up button, so long
		// as you specify a parent activity in AndroidManifest.xml.
		int id = item.getItemId();

		//noinspection SimplifiableIfStatement
		if (id == R.id.action_settings) {
			return true;
		}

		return super.onOptionsItemSelected(item);
	}

	/**
	 * A placeholder fragment containing a simple view.
	 */
	public static class PlaceholderFragment extends Fragment {

		public PlaceholderFragment() {

		}

		@Override
		public View onCreateView(LayoutInflater inflater, ViewGroup container,
		                         Bundle savedInstanceState) {
			View rootView = inflater.inflate(R.layout.fragment_main, container, false);
		//	Log.i("shit",this.getTag());
			GridView gv = (GridView) rootView.findViewById(R.id.poemList);
			gv.setAdapter( new PoemListAdapter( inflater  ));
			gv.setOnItemClickListener( new PoemItemEvent.Click());
			return rootView;
		}
	}

	/**
	 * A poem fragment
	 */
	public static class PoemFragment extends Fragment {

		public PoemFragment(){
		}

		@Override
		public View onCreateView(LayoutInflater inflater, ViewGroup container,
		                         Bundle savedInstanceState) {
			View rootView = inflater.inflate(R.layout.fragment_poem, container, false);
			Poem poem = (Poem)this.getArguments().getSerializable("poem");
			((TextView)rootView.findViewById(R.id.title)).setText(poem.getTitle());
			((TextView)rootView.findViewById(R.id.author)).setText(poem.getName());

			ListView lv = (ListView)rootView.findViewById(R.id.content);
			ArrayAdapter aa = new ArrayAdapter( rootView.getContext() , R.layout.poem_content ,R.id.poem_content_item , poem.getContent());
			lv.setAdapter(aa);

			return rootView;
		}
	}
}
