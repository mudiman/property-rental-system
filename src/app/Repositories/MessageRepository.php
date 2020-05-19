<?php

namespace App\Repositories;

use App\Models\Message;

class MessageRepository extends ParentRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'by_user',
        'thread_id',
        'title',
        'message',
        'status',
        'messageable_id',
        'messageable_type',
        'snapshot',
        'archived',
        'updated_by',
        'created_by',
        'deleted_by'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Message::class;
    }
    

    public function dispatchNotification($notificationConfig, $model, $from, $to) {

    }
    
    
    public function getPropertiesIds($messages) {
      $propertyIds = [];
      foreach ($messages as $message) {
        if (in_array($message->messageable_type, [\App\Models\Offer::morphClass, \App\Models\Tenancy::morphClass, \App\Models\Viewing::morphClass])
            && !empty($message->messageable)) {
          $propertyIds[] = $message->messageable->property_id;
        }
      }
      return array_unique($propertyIds);
    }
    
    public function getViewingRequestIds($messages) {
      $viewingIds = [];
      foreach ($messages as $message) {
        if (in_array($message->messageable_type, [\App\Models\ViewingRequest::morphClass])
            && !empty($message->messageable)) {
          $viewingIds[] = $message->messageable->viewing_id;
        }
      }
      return array_unique($viewingIds);
    }
    
    public function getViewingIds($messages) {
      $propertyIds = [];
      foreach ($messages as $message) {
        if (in_array($message->messageable_type, [\App\Models\Viewing::morphClass])
            && !empty($message->messageable)) {
          $propertyIds[] = $message->messageable->property_id;
        }
      }
      return array_unique($propertyIds);
    }
    
    public function getLikeIds($messages) {
      $likeIds = [];
      foreach ($messages as $message) {
        if (in_array($message->messageable_type, [\App\Models\Like::morphClass])
            && !empty($message->messageable)) {
          $likeIds[] = $message->messageable->id;
        }
      }
      return array_unique($likeIds);
    }
    
    public function getProperties($propertyIds)
    {
      $propertiesMap = [];
      $propeties = \App\Models\Property::whereIn('id', $propertyIds)->get();
      foreach ($propeties as $property) {
        $propertiesMap[$property->id] = $property;
      }
      return $propertiesMap;
    }
    
    public function getViewingRequests($viewingIds)
    {
      $viewingMap = [];
      $viewings = \App\Models\Viewing::whereIn('id', $viewingIds)->with('property')->get();
      foreach ($viewings as $viewing) {
        $viewingMap[$viewing->id] = $viewing;
      }
      return $viewingMap;
    }
    
    public function getlikes($likeIds)
    {
      $likesMap = [];
      $likes = \App\Models\Like::whereIn('id', $likeIds)->with('likeable')->get();
      foreach ($likes as $like) {
        $likesMap[$like->id] = $like;
      }
      return $likesMap;
    }
    
    public function attachRelation(&$messages, $propertiesMap, $viewingMap, $likeMap) {
      foreach ($messages as $message) {
        if (empty($message->messageable)) continue;
        if (in_array($message->messageable_type, [\App\Models\Offer::morphClass, \App\Models\Tenancy::morphClass, \App\Models\Viewing::morphClass])) {
          $message->messageable->property = $propertiesMap[$message->messageable->property_id];
          
        } elseif (in_array($message->messageable_type, [\App\Models\ViewingRequest::morphClass])) {
          $message->messageable->viewing = $viewingMap[$message->messageable->viewing_id];
          
        } elseif (in_array($message->messageable_type, [\App\Models\Like::morphClass])) {
          $message->messageable->likeable = $likeMap[$message->messageable->id]->likeable;
        }
      }
    }

}
