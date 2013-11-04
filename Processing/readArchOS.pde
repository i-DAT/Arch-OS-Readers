import processing.xml.*;

XMLElement xml;
XMLElement[] title;
XMLElement[] pubDate;
XMLElement[] value;


void setup() 
{
  // Download RSS feed of news stories from yahoo.com
  String url = "http://www.arch-os.com/livedata/";
  XMLElement rss = new XMLElement(this, url);
  // Get all <link> elements
  title = rss.getChildren("channel/item/title");
  pubDate = rss.getChildren("channel/item/pubDate");
  value = rss.getChildren("channel/item/description");
}

  
  void draw()
  {
    for (int i = 0; i < title.length; i++)
    {
      println("Name: " + title[i].getContent() + " Last time collected: " + pubDate[i].getContent() + " Value: " + value[i].getContent());
    }
    
  }

