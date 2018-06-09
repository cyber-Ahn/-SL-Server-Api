string your_key = "24456546352";
key reqid;
key url_request;
string id_http;
send(string key_,string actions,string var)
{
    reqid = llHTTPRequest("http://cyber.caworks-sl.de/API/api.php?key="+key_+"&action="+actions+"&var="+llEscapeURL(var), [], "");
}
send_obj(string key_,string actions,string var,string chan,string obid)
{
    reqid = llHTTPRequest("http://cyber.caworks-sl.de/API/api.php?key="+key_+"&action="+actions+"&var="+llEscapeURL(var)+"&chan="+chan+"&obid="+obid, [], "");
} 
integer x; 
abfrage()
{
    x++;
    if(x == 1)
    {
        // search avatar key
        send(your_key,"n2k","cyber Ahn");
    }
    if(x == 2)
    {
        //search sim texture
        send(your_key,"simmap","Lychee Island");
    }
    if(x == 3)
    {
        // search gate data example form 5 points
        send(your_key,"gatesearch","5 points");
    }
    if(x == 4)
    {
        // submit message to other object
        send_obj(your_key,"comm","your message","1",id_http);
    }
} 
default
{
    state_entry()
    {
        llOpenRemoteDataChannel();
    }
    touch_start(integer total_number)
    {
        x=0;
        abfrage();
    }
    http_response(key id, integer status, list meta, string body)
    {
        if ( id == reqid )
        {
            llSay(0,body);
            abfrage();
        }
    }
    remote_data(integer type, key channel, key message_id, string sender, integer ival, string sval) 
    {
        if (type == REMOTE_DATA_CHANNEL) 
        { 
            id_http = channel;
            llOwnerSay( "http_uuid = "+ id_http);
        }
        if (type == REMOTE_DATA_REQUEST) 
        {
            llSay(0,"Message = "+sval+" and Channel = "+(string)ival);
        }
    }
}