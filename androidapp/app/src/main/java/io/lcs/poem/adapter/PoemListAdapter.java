package io.lcs.poem.adapter;

import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.BaseAdapter;
import android.widget.TextView;

import java.text.Collator;
import java.util.Collections;
import java.util.Comparator;

import io.lcs.poem.R;
import io.lcs.poem.dao.PoemDao;
import io.lcs.poem.pojo.Poem;

/**
 * Created by john on 2014/11/8.
 */
public class PoemListAdapter extends BaseAdapter {
	private PoemDao poemDao ;
	private LayoutInflater inflater;

	public PoemListAdapter( LayoutInflater inflater ){
		this.inflater = inflater;
		this.poemDao = new PoemDao( this.inflater.getContext().getResources().openRawResource(R.raw.poem) );

	}

	@Override
	public int getCount() {
		return this.poemDao.getPoemList().size();
	}

	@Override
	public Poem getItem(int i) {
		return this.poemDao.getPoemList().get(i);
	}

	@Override
	public long getItemId(int i) {
		return this.poemDao.getPoemList().get(i).getPoemId();
	}

	@Override
	public View getView(int i, View view, ViewGroup viewGroup) {
		if( view == null ){
			view = this.inflater.inflate(R.layout.poem_item, null , false);
		}

		Poem poem = this.getItem(i);
		((TextView)view.findViewById(R.id.poemItemAuthor)).setText(poem.getName());
		((TextView)view.findViewById(R.id.poemItemTitle)).setText(poem.getTitle());

		view.setBackgroundResource(  i%2 == 0 ? R.color.poem_item : R.color.poem_item_2 );
		view.setTag( poem );

		return view;
	}

	public void update( String key ){
		if( key != null ){
			key = key.trim();
		}
		this.poemDao.update(key);
		this.notifyDataSetChanged();
	}


}
