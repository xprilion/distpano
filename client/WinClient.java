import java.awt.*;
import java.awt.event.*;
import java.io.*;
import java.net.*;
import java .util.*;

public class Client {

    public static String gopto()
    {

        String res = "";

        try
        {

            Runtime.getRuntime().exec(" pto_gen -o project.pto v.jpg h.jpg");
            Runtime.getRuntime().exec(" cpfind --multirow -o output.pto project.pto");
            File file = new File("/home/xprilion/webroot/sih18server/client/sih18/output.pto");

            BufferedReader br = new BufferedReader(new FileReader(file));

            String st;
            String a[]=new String[2];

            String b[]=new String[2];

            int ncount = 0;

            String coordString;

            while ((st = br.readLine())!= null)
            {
                if(st.contains("c n0 N1"))
                {

                    String [] crds = new String[4];

                    coordString = st.substring(8, st.length() -3);

                    crds = coordString.split(" ");

                    a[0] = (crds[0].substring(1));
                    a[1] = (crds[1].substring(1));
                    b[0]= (crds[2].substring(1));
                    b[1] = (crds[3].substring(1));

                    System.out.print(a[0] +","+ a[1] +"|"+ b[0]+ ","+ b[1] + ";");
                    res += a[0] +","+ a[1] +"|"+ b[0]+ ","+ b[1] + ";";

                    ncount++;

                }
            }

            System.out.println("Ho gaya jo ho sakta tha");

        }
        catch (Exception e)
        {
            System.out.println("HEY Buddy ! U r Doing Something Wrong ");
            e.printStackTrace();
            res = "error";
        }

        return res;
    }

    public static void copyURLToFile(URL url, File file) {

        try {
            InputStream input = url.openStream();
            if (file.exists()) {
                if (file.isDirectory())
                    throw new IOException("File '" + file + "' is a directory");

                if (!file.canWrite())
                    throw new IOException("File '" + file + "' cannot be written");
            } else {
                File parent = file.getParentFile();
                if ((parent != null) && (!parent.exists()) && (!parent.mkdirs())) {
                    throw new IOException("File '" + file + "' could not be created");
                }
            }

            FileOutputStream output = new FileOutputStream(file);

            byte[] buffer = new byte[4096];
            int n = 0;
            while (-1 != (n = input.read(buffer))) {
                output.write(buffer, 0, n);
            }

            input.close();
            output.close();

            System.out.println("File '" + file + "' downloaded successfully!");
        }
        catch(IOException ioEx) {
            ioEx.printStackTrace();
        }
    }


    public static void main(String[] args){

        Frame f=new Frame("Sih18Client");
		f.setBounds(450,200,0,0);

        final Label tf=new Label("Fusion2");
        tf.setBounds(80,70,200,100);

        Button b1=new Button("Start");
        b1.setBounds(40,320,80,40);
		
		Button b2=new Button("Stop");
        b2.setBounds(280,320,80,40);
		
        b1.addActionListener(new ActionListener(){
            public void actionPerformed(ActionEvent e){
                    String stringToReverse = "https://sih.phoenixnsec.org/taskAllocator.php";
                    // String stringToReverse = "https://facebook.com";
                     // URL 100,200,200,40url = new URL(args[0]);
                try {
                    URL url = new URL(stringToReverse);
                    try{
                        URLConnection connection = url.openConnection();
                        connection.setDoOutput(true);
                        BufferedReader in = new BufferedReader(new InputStreamReader(connection.getInputStream()));
                        String decodedString;
                        String s = "";
                        while ((decodedString = in.readLine()) != null) {
                            //System.out.println(decodedString);
                            s += decodedString;
                        }

                        System.out.println("KJSDLSJD: "+s);

                        if(s!=null)
                        {

                            //System.out.println("KJSDLSJD: "+s);

                            String taskhash= s.substring(31,63);
                            String imghash1= s.substring(77,109);
                            String imghash2= s.substring(123,155);
                            String h= s.substring(162,163);
                            String v= s.substring(170,171);
                            String ext1= s.substring(181,184);
                            String ext2= s.substring(194,197);
                            System.out.println("taskhash:"+taskhash);
                            System.out.println("imagehash1:"+imghash1);

                            System.out.println("imagehash2:"+imghash2);
                            System.out.println("h:"+h);
                            System.out.println("v:"+v);
                            System.out.println("ect1:"+ext1);
                            System.out.println("ect2:"+ext2);

                            String imgurl1 = "https://sih.phoenixnsec.org/staged/"+taskhash+"/"+imghash1+"/h/"+h+"."+ext1;
                            System.out.println(imgurl1);
                            String imgurl2 = "https://sih.phoenixnsec.org/staged/"+taskhash+"/"+imghash2+"/v/"+v+"."+ext2;
                            System.out.println(imgurl2);

                            URL urlH = new URL(imgurl1);
                            URL urlV = new URL(imgurl2);

                            File fileH = new File("h.jpg");
                            File fileV = new File("v.jpg");

                            Client.copyURLToFile(urlH, fileH);
                            Client.copyURLToFile(urlV, fileV);

                            String res = Client.gopto();

                            String resUrl = "https://sih.phoenixnsec.org/clientSendResult.php?taskhash=" + taskhash + "&imghash1=" + imghash1 + "&imghash2=" + imghash2 + "&h=" + h + "&v=" + v + "&res=" + res;

                            System.out.println("Result Call: " + resUrl);

                            URL resGo = new URL(resUrl);
                            URLConnection resConn = resGo.openConnection();
                            resConn.setDoOutput(true);
                            BufferedReader resin = new BufferedReader(new InputStreamReader(resConn.getInputStream()));
                            String decodedRes;
                            while ((decodedRes = resin.readLine()) != null) {
                                System.out.println(decodedRes);
                            }

                        }

                    }
                    catch(IOException eee){

                    }
                }
                catch(IOException ee){

                }
            }
        });

        f.addWindowListener( new WindowAdapter() {
            @Override
            public void windowClosing(WindowEvent we) {
                System.exit(0);
            }
        } );

        f.add(b1);
        f.add(b2);
        f.add(tf);
        f.setSize(400,400);
        f.setLayout(null);
        f.setVisible(true);
    }
}