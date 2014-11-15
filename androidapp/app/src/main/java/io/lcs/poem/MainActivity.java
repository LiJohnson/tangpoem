package io.lcs.poem;

import android.app.ActionBar;
import android.app.Activity;
import android.app.Fragment;
import android.app.FragmentManager;
import android.os.Bundle;
import android.view.KeyEvent;
import android.view.LayoutInflater;
import android.view.Menu;
import android.view.MenuItem;
import android.view.View;
import android.view.ViewGroup;
import android.widget.AdapterView;
import android.widget.ArrayAdapter;
import android.widget.GridView;
import android.widget.ListView;
import android.widget.TextView;

import io.lcs.poem.adapter.PoemListAdapter;
import io.lcs.poem.event.PoemItemEvent;
import io.lcs.poem.pojo.Poem;


public class MainActivity extends Activity {
	private Fragment mainFragment;
	private Fragment poemFragment;

	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		this.setContentView(R.layout.activity_main);
		if (savedInstanceState == null) {
			this.mainFragment = new MainFragment();
			this.getFragmentManager().beginTransaction()
					.add(R.id.container, this.mainFragment)
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
		}else if( id == android.R.id.home ){
			this.getFragmentManager().popBackStack();
			return false;
		}
		return super.onOptionsItemSelected(item);
	}

	public void showPoem( Poem poem ){
		this.poemFragment = new PoemFragment();
		Bundle b = new Bundle();
		b.putSerializable("poem",poem);
		this.poemFragment.setArguments(b);

		this.getFragmentManager().beginTransaction()
				.replace(R.id.container,this.poemFragment)
				.addToBackStack(null)
				.commit();
	}

	/**
	 * A placeholder fragment containing a simple view.
	 */
	public static class MainFragment extends Fragment {
		public MainFragment() {
		}

		@Override
		public View onCreateView(LayoutInflater inflater, ViewGroup container,
		                         Bundle savedInstanceState) {
			final MainActivity activity = (MainActivity) this.getActivity();
			View rootView = inflater.inflate(R.layout.fragment_main, container, false);
			GridView gv = (GridView) rootView.findViewById(R.id.poemList);
			gv.setAdapter(new PoemListAdapter(inflater));

			gv.setOnItemClickListener(new AdapterView.OnItemClickListener() {
				@Override
				public void onItemClick(AdapterView<?> parent, View view, int position, long id) {
					activity.showPoem((Poem) view.getTag());
				}
			});

			return rootView;
		}
	}

	/**
	 * A poem fragment
	 */
	public static class PoemFragment extends Fragment {
		private ActionBar actionBar;
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

		@Override
		public void onAttach(Activity activity) {
			super.onAttach(activity);

			this.actionBar = activity.getActionBar();
			this.actionBar.setDisplayHomeAsUpEnabled(true);
			this.actionBar.setDisplayShowHomeEnabled(false);
		}

		@Override
		public void onDetach(){
			super.onDetach();

			if( this.actionBar == null )return;

			this.actionBar.setDisplayShowHomeEnabled(true);
			this.actionBar.setDisplayHomeAsUpEnabled(false);
		}
	}
}
