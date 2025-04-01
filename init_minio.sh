#!/bin/sh

COUNTER=0
MAX_RETRIES=20

# Wait until MinIO is ready and set up the alias
while ! mc alias set myminio http://minio:9000 minioadmin minioadmin; do
  sleep 2
  COUNTER=$((COUNTER + 1))
  if [ "$COUNTER" -ge "$MAX_RETRIES" ]; then
    echo "Error: MinIO did not start after multiple attempts."
    exit 1
  fi
done

echo "MinIO started! Configuring MinIO Client..."

# Check if the bucket exists
if mc ls myminio/testebucket >/dev/null 2>&1; then
  echo "Bucket 'testebucket' already exists."
else
  echo "Creating bucket 'testebucket'..."
  mc mb myminio/testebucket
  if [ $? -eq 0 ]; then
    echo "Bucket 'testebucket' created successfully!"
  else
    echo "Error creating bucket!"
    exit 1
  fi
fi

echo "MinIO configuration completed successfully!"
exit 0  # Exit script normally
