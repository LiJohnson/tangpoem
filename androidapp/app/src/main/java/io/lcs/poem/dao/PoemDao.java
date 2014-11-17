package io.lcs.poem.dao;

import com.google.gson.Gson;
import com.google.gson.reflect.TypeToken;

import java.io.InputStream;
import java.io.InputStreamReader;
import java.lang.reflect.Type;
import java.text.Collator;
import java.util.ArrayList;
import java.util.Collections;
import java.util.Comparator;
import java.util.List;

import io.lcs.poem.pojo.Poem;


/**
 * Created by john on 2014/11/8.
 */
public class PoemDao {
	private List<Poem> poemList;
	private List<Poem> baseList;

	public PoemDao( InputStream inputStream ) {
		Gson g = new Gson( );
		Type listType = new TypeToken<List<Poem>>(){}.getType();
		this.baseList = g.fromJson(new InputStreamReader( inputStream ), listType);
		Collections.sort(this.baseList, new PYSort());
		this.poemList = this.copy( this.baseList );
	}

	public List<Poem> getPoemList() {
		return this.poemList;
	}

	public void update( String key ){
		if( key == null || "".equals(key) ){
			this.poemList = this.copy( this.baseList );
			return;
		}

		this.poemList.clear();
		for( Poem poem : this.baseList ){
			if( poem.getTitle().indexOf(key) != -1 || poem.getName().indexOf(key) != -1 ){
				this.poemList.add(poem);
			}
		}
	}

	private List<Poem> copy( List<Poem> list){
		List<Poem> newList = new ArrayList<Poem>();
		list = list == null ? new ArrayList<Poem>() : list;

		for( Poem poem : list ){
			newList.add(poem);
		}

		return newList;
	}

	private static class PYSort implements Comparator<Poem> {
		@Override
		public int compare(Poem poem, Poem poem2) {
			Comparator cmp = Collator.getInstance(java.util.Locale.CHINA);
			return cmp.compare( poem.getTitle() , poem2.getTitle() );
		}
	}
}