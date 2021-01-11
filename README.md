# xcash-dpops-sweep
PHP script to send all balance from the DPoPS node to an external wallet.
Useful if you don't want stop DPOPS and send manually from the `xcash-wallet-cli` binary and lose uptime and opportunities to produce a block.

I've used PHP because lot of people in the community feels comfortable with PHP.

### Prerequisites

First of all, we need to install php and php-curl

```sh
apt install -y php php-curl
```


### Installation

1. Clone the repo in any directory of your local node. In my case I will use `~/xcash-official/scripts/`
   ```sh
   cd ~/xcash-official
   git clone https://github.com/rradu92/xcash-dpops-sweep.git scripts
   ```

2. Once done, change the `$pay_to` variable to your own wallet address, replace `<address>` with your own address in the snippet below:
   # BE CAREFUL AND MAKE SURE TO USE YOUR ADDRESS HERE
   ```sh
   cd scripts && sed -i s"|PUT_YOUR_ADDRESS_HERE|<address>|" sweep_account.php 
   ```

3. Done, now you can make a cron job to execute it every hour or every day, or whatever you want, for example:
   For the root user:
   ```sh
   0 * * * * php /root/xcash-official/scripts/sweep_account.php > /root/xcash-official/logs/sweep_account.log
   ```

   For other user:
   ```sh
   0 * * * * php /home/<user>/xcash-official/scripts/sweep_account.php > /home/<user>/xcash-official/logs/sweep_account.log
   ```

   * You can see the log in `/root/xcash-official/logs/sweep_account.log` if you installed as root or `/home/<user>/xcash-official/logs/sweep_account.log` if you installed as normal user.
   * NOTE: To edit the cron content you need to execute `crontab -e` 
