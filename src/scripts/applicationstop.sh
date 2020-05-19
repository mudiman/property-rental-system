#!/bin/bash
instanceId=$(curl --silent 'http://169.254.169.254/latest/meta-data/instance-id' 2> /dev/null)
autoGroup=$(sudo aws autoscaling describe-auto-scaling-instances --instance-ids  $instanceId | jq '.AutoScalingInstances[0].AutoScalingGroupName')
trimAutoGroup="${autoGroup//\"}"
loadBalancer=$(sudo aws autoscaling describe-load-balancers --auto-scaling-group-name $trimAutoGroup | jq '.LoadBalancers[0].LoadBalancerName')
trimLoadBalancer="${loadBalancer//\"}"
sudo aws elb deregister-instances-from-load-balancer --load-balancer-name $trimLoadBalancer --instances $instanceId