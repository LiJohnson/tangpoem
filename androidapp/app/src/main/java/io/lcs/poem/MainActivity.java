package io.lcs.poem;

import android.app.ActionBar;
import android.app.Activity;
import android.app.Fragment;
import android.app.FragmentManager;
import android.content.Intent;
import android.net.Uri;
import android.os.Bundle;
import android.text.Editable;
import android.text.TextWatcher;
import android.util.Log;
import android.view.GestureDetector;
import android.view.LayoutInflater;
import android.view.Menu;
import android.view.MenuItem;
import android.view.MotionEvent;
import android.view.View;
import android.view.ViewGroup;
import android.widget.AdapterView;
import android.widget.ArrayAdapter;
import android.widget.EditText;
import android.widget.GridView;
import android.widget.ListView;
import android.widget.SearchView;
import android.widget.TextView;

import io.lcs.poem.adapter.PoemListAdapter;
import io.lcs.poem.event.PoemEvent;
import io.lcs.poem.pojo.Poem;


public class MainActivity extends Activity  implements GestureDetector.OnGestureListener {
	private MainFragment mainFragment;
	private PoemFragment poemFragment;
	private GestureDetector gestureDetector;
	private SearchView searchView;
	private Menu menu;
	private FragmentManager fragmentManager;

	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		this.setContentView(R.layout.activity_main);

		if (savedInstanceState == null) {
			fragmentManager = this.getFragmentManager();
			this.gestureDetector = new GestureDetector( this.getApplicationContext() , new PoemEvent.OnSwipe() {
				@Override
				public boolean swipeLeft() {
					return false;
				}

				@Override
				public boolean swipeRight() {
					fragmentManager.popBackStack();
					return false;
				}
			});
			this.mainFragment = new MainFragment();
			fragmentManager.beginTransaction()
					.add(R.id.container, this.mainFragment)
					.commit();
		}
	}

	@Override
	public boolean onCreateOptionsMenu(Menu menu) {
		// Inflate the menu; this adds items to the action bar if it is present.
		getMenuInflater().inflate(R.menu.menu_main, menu);

		this.menu = menu;

		searchView = (SearchView) menu.findItem(R.id.menu_search).getActionView();

		searchView.setOnQueryTextListener(new SearchView.OnQueryTextListener() {
			@Override
			public boolean onQueryTextSubmit(String s) {
				mainFragment.search(s);
				return true;
			}

			@Override
			public boolean onQueryTextChange(String s) {
				mainFragment.search(s);
				return false;
			}
		});

		this.searchView.setOnQueryTextFocusChangeListener( new View.OnFocusChangeListener() {
			@Override
			public void onFocusChange(View view, boolean isFocus) {
				if( isFocus ){
					fragmentManager.popBackStack();
					mainFragment.search(searchView.getQuery().toString());
				}
			}
		});
		return true;
	}

	@Override
	public boolean onOptionsItemSelected(MenuItem item) {
		// Handle action bar item clicks here. The action bar will
		// automatically handle clicks on the Home/Up button, so long
		// as you specify a parent activity in AndroidManifest.xml.
		int id = item.getItemId();

		//noinspection SimplifiableIfStatement
		if( id == android.R.id.home ){
			this.getFragmentManager().popBackStack();
			return false;
		}

		return super.onOptionsItemSelected(item);
	}

	@Override
	public boolean onTouchEvent( MotionEvent event ){
		return this.gestureDetector.onTouchEvent(event);
	}

	public void showPoem( Poem poem ){
		this.poemFragment = new PoemFragment();
		Bundle b = new Bundle();
		b.putSerializable("poem",poem);
		this.poemFragment.setArguments(b);

		this.searchView.onActionViewCollapsed();

		this.getFragmentManager().beginTransaction()
				//.setCustomAnimations( android.R.animator.fade_in , android.R.animator.fade_out )
				.setCustomAnimations(R.anim.slide_left_in, R.anim.slide_left_out,R.anim.slide_right_in, R.anim.slide_right_out)
				.replace(R.id.container, this.poemFragment)
				.addToBackStack(null)
				.commit();
	}

	public String getSearchKey(){
		if( this.searchView == null || this.searchView.getQuery() == null ){
			return null;
		}
		return this.searchView.getQuery().toString();
	}

	@Override
	public boolean onDown(MotionEvent motionEvent) {
		return false;
	}

	@Override
	public void onShowPress(MotionEvent motionEvent) {

	}

	@Override
	public boolean onSingleTapUp(MotionEvent motionEvent) {
		return false;
	}

	@Override
	public boolean onScroll(MotionEvent motionEvent, MotionEvent motionEvent2, float v, float v2) {
		return false;
	}

	@Override
	public void onLongPress(MotionEvent motionEvent) {

	}

	@Override
	public boolean onFling(MotionEvent motionEvent, MotionEvent motionEvent2, float v, float v2) {
		Log.i("shit","onFling");
	return false;
	}

	/**
	 * A placeholder fragment containing a simple view.
	 */
	public static class MainFragment extends Fragment {
		private PoemListAdapter adapter;
		public MainFragment() {
		}

		@Override
		public View onCreateView(LayoutInflater inflater, ViewGroup container,
		                         Bundle savedInstanceState) {
			final MainActivity activity = (MainActivity) this.getActivity();

			adapter = new PoemListAdapter(inflater);
			this.adapter.update( activity.getSearchKey() );

			View rootView = inflater.inflate(R.layout.fragment_main, container, false);
			GridView gv = (GridView) rootView.findViewById(R.id.poemList);
			gv.setAdapter(adapter);

			gv.setOnItemClickListener(new AdapterView.OnItemClickListener() {
				@Override
				public void onItemClick(AdapterView<?> parent, View view, int position, long id) {
					activity.showPoem((Poem) view.getTag());
				}
			});
			Log.i("shit","onCreateView");
			return rootView;
		}

		public void search( String key ){
			if( this.adapter == null){
				return;
			}
			this.adapter.update( key );
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

			final Poem poem = (Poem)this.getArguments().getSerializable("poem");
			((TextView)rootView.findViewById(R.id.title)).setText(poem.getTitle());
			((TextView)rootView.findViewById(R.id.author)).setText(poem.getName());

			ListView lv = (ListView)rootView.findViewById(R.id.content);
			ArrayAdapter aa = new ArrayAdapter( rootView.getContext() , R.layout.poem_content ,R.id.poem_content_item , poem.getContent());
			lv.setAdapter(aa);

			rootView.findViewById(R.id.poemLink);
			rootView.setOnClickListener(new View.OnClickListener() {
				@Override
				public void onClick(View v) {
					Uri uri = Uri.parse(getString(R.string.poem_url) + poem.getPoemId());
					Intent intent = new Intent(Intent.ACTION_VIEW, uri);
					startActivity(intent);
				}
			});

			return rootView;
		}

		@Override
		public void onAttach(Activity activity) {
			super.onAttach(activity);

			this.actionBar = activity.getActionBar();

			if( this.actionBar == null )return;

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
