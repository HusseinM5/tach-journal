# LizardFS Documentation

![](https://github.com/HusseinM5/tech-journal/blob/main/SYS265/DFSProject/imgs/image1.png)

# 1- Network Configuration:
Our Network includes the following VMs:

2x Ubuntu (192.168.1.160 & 192.168.1.161)
Windows 10 (192.168.1.162)

## Ubuntu

Open `/etc/netplan/00-installer-config.yaml` and add the following to it:

```
network:
  version: 2
  renderer: networkd
  ethernets:
    ens160:
      addresses:
        - <IP>/24
      gateway4: 192.168.8.1
      nameservers:
          addresses: [8.8.8.8,8.8.4.4]
```
After saving the file run the command below:
```
# netplan apply
```

## Windows

Open `Network & Internet settings`, then click on `Change adapter options`. Which will show Ethernet0 depending on the used adapter. Open the properties and select `Internet Protocol Version 4` and set the IP Address according to what was provided. You can see an  example in Figure 1-1.


![](https://github.com/HusseinM5/tech-journal/blob/main/SYS265/DFSProject/imgs/image2.png)

Figure 1-1: Windows network settings



## Edit /etc/hosts

On all VMs you need to have these entries in your `/etc/hosts`:

```
192.168.8.160    mfsmaster
192.168.8.161    chunkserver1
```

Note: You can add one to the Windows VM, but it is not required.
# 2- Master Installation:
LizardFS is available as a Debian or Ubuntu package, and can be installed with:
```bash
# apt install lizardfs-master
```
### Configuration
Before starting the service, the config files provided by the package need to be moved to the proper locations. They are located in ```/usr/share/doc/lizardfs/examples/``` and have to be moved to ```/etc/lizardfs/``` This can be done with:
```bash
sudo cp /usr/share/doc/lizardfs/examples/* /etc/lizardfs/
```

There also needs to be a config file in ```/var/lib/lizardfs/``` to include metadata about the server. An empty (default) one can be found in the same directory, but needs to be copied or renamed to remove the ```.empty``` file extension:
```bash
sudo cp /var/lib/lizardfs/metadata.mfs.empty /var/lib/lizardfs/metadata.mfs
```

Note: after starting the service with ```sudo systemctl start lizardfs``` the metadata file may be filled with information. Re-copy the empty metadata config file if this is the case.
# 3- Chunk Server Installation
LizardFS chunkserver is available as a Debian or Ubuntu package, and can be installed with:
```bash
# apt install lizardfs-chunkserver
```
### Configuration
Similar to the master server, the default config file needs to be copied to the /etc/lizardfs directory. However, for the chunk server, the files are stored in a gz zipped directory. It can be unzipped using:
```bash
dz -d /usr/share/doc/lizardfs-chunkserver/examples/mfschunkserver.cfg.gz
```
Where the file share we configured is relatively small, there is one necessary change in the default configuration. Typically, LizardFS will report a drive as full when there is only 4GiB remaining in the drive. We can change this by uncommenting and modifying the value ```HDD_LEAVE_SPACE_DEFAULT``` in mfschunkserver.cfg from 4GiB to something small like 1MiB.

In `mfshdd.cfg` add a line where your disks are located at. You can see an example in Figure 1-2.


![](https://github.com/HusseinM5/tech-journal/blob/main/SYS265/DFSProject/imgs/image3.png)

Figure 1-2: Chunkserver disks


Then, copy the files to lizardfs and restart the service.
```bash
sudo cp /usr/share/doc/lizardfs-chunkserver/examples/* /etc/lizardfs/
sudo systemctl restart lizardfs-chunkserver.service
```

### Creating Disks
We can create a empty file using the following command:
```
# fallocate -l 1024M image.iso
```
Then we have to turn it intoa virtual disk using the following command:
```
# mkfs.ext4 -j image.iso
```
The last thing to do is to mount that virtual disk using the following command:
```
# mount -t ext4 image.iso /mnt/disk
```
Make sure to change the owner of the directory to the `lizardfs` user and group:
```
# chown -R lizardfs:lizardfs /mnt/disk
```

# 4- Windows Installation
Install Lizardfs windows client from here. Run the downloaded binary and leave everything to the default. After that start Lizardfs and make it connect to mfsmaster as seen in Figure 1-3.

![](https://github.com/HusseinM5/tech-journal/blob/main/SYS265/DFSProject/imgs/image4.png)

Figure 1-3: Lizardfs Windows client

## Configuring IIS

IIS can be enabled on Windows 10 by searching “Features” in the start menu and selecting “Turn Windows features on or off.” Select “Internet Information Services” and click OK.

In `Internet Information Services (IIS) Manager` open the advance setting of your website, and set the Physical Path to where you mounted the Lizardfs disk. While you are in the main page of the manager enable `Directory Browsing` to be able to view the files on the mountable disk without changing any permissions.


### Reflection

The project was cool to explore, as it helped understanding how large enterprises like Google works. I wish there was more clarification in the project requirements, because some stuff were unclear at all. During the project we used LizardFS, which it wasn't that bad other than the documentation was terrible and outdated. At this point it seems most of the open source project similar to this one are not the best to go with just because of how many issues you will run into.
